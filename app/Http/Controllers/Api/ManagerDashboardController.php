<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyVisit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerDashboardController extends Controller
{
    public function summary()
    {
        $today = Carbon::today();
        $weekAgo = $today->copy()->subDays(6);

        $totals = [
            'total_visits' => LoyaltyVisit::count(),
            'pending_visits' => LoyaltyVisit::where('status', 'pending')->count(),
            'confirmed_visits' => LoyaltyVisit::where('status', 'confirmed')->count(),
            'points_distributed' => LoyaltyVisit::sum('points_awarded'),
        ];

        $daily = LoyaltyVisit::selectRaw('DATE(created_at) as day, COUNT(*) as visits')
            ->whereBetween('created_at', [$weekAgo->toDateString(), $today->toDateString()])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn ($row) => [
                'day' => $row->day,
                'visits' => (int) $row->visits,
            ]);

        return response()->json([
            'totals' => $totals,
            'daily_visits' => $daily,
        ]);
    }

    public function servers()
    {
        $servers = User::where('role', 'server')
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'active' => (bool) $user->active,
                'last_login' => $user->updated_at?->toIso8601String(),
            ]);

        return response()->json([
            'servers' => $servers,
        ]);
    }

    public function toggleServer(Request $request, User $user)
    {
        if (!$user->isServer()) {
            return response()->json([
                'message' => 'Solo puedes administrar meseros.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->active = !$user->active;
        $user->save();

        return response()->json([
            'message' => $user->active ? 'Mesero activado.' : 'Mesero desactivado.',
            'server' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'active' => (bool) $user->active,
            ],
        ]);
    }
}
