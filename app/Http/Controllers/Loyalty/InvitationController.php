<?php

namespace App\Http\Controllers\Loyalty;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->query('email'))
            ->whereNotNull('invitation_token')
            ->firstOrFail();

        if (!Hash::check($request->query('token'), $user->invitation_token)) {
            abort(403, 'Invitación inválida o vencida.');
        }

        return view('loyalty.invitations.show', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        if (!$user->invitation_token || !Hash::check($data['token'], $user->invitation_token)) {
            return back()->withErrors(['token' => 'El enlace expiró, solicita una nueva invitación.']);
        }

        $user->forceFill([
            'password' => $data['password'],
            'invitation_token' => null,
            'invitation_accepted_at' => now(),
        ])->save();

        auth()->login($user);

        return redirect()->route($user->isServer() ? 'loyalty.dashboard' : 'admin.new-panel')
            ->with('success', 'Acceso activado.');
    }
}
