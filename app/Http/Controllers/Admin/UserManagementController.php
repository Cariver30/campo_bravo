<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function storeManager(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'manager',
            'active' => true,
        ]);

        return back()->with('success', 'Gerente creado correctamente.');
    }

    public function toggleManager(User $user)
    {
        abort_unless($user->isManager(), 403);

        $user->update(['active' => ! $user->active]);

        return back()->with('success', $user->active ? 'Gerente activado.' : 'Gerente bloqueado.');
    }

    public function destroyManager(User $user)
    {
        abort_unless($user->isManager(), 403);

        $user->delete();

        return back()->with('success', 'Gerente eliminado.');
    }
}
