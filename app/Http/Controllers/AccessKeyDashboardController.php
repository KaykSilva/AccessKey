<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Models\KeyMovement;
use App\Models\KeyPerson;
use App\Models\KeyReservation;
use App\Models\KeyResponsible;
use Inertia\Inertia;

class AccessKeyDashboardController
{
    public function __invoke()
    {
        return Inertia::render('AccessKey/Dashboard', [
            'stats' => [
                'total_keys' => Key::count(),
                'available_keys' => Key::where('status', 'available')->count(),
                'borrowed_keys' => Key::where('status', 'borrowed')->count(),
                'maintenance_keys' => Key::where('status', 'maintenance')->count(),
                'people' => KeyPerson::count(),
                'responsibles' => KeyResponsible::count(),
                'pending_reservations' => KeyReservation::where('status', 'pending')->count(),
                'open_movements' => KeyMovement::where('status', 'borrowed')->count(),
            ],

            'recentMovements' => KeyMovement::with([
                'key',
                'person',
                'takenByUser',
                'deliveredByUser',
                'receivedByUser',
            ])
                ->latest()
                ->limit(8)
                ->get(),

            'upcomingReservations' => KeyReservation::with(['key', 'person'])
                ->whereIn('status', ['pending', 'approved'])
                ->where('start_at', '>=', now())
                ->orderBy('start_at')
                ->limit(8)
                ->get(),
        ]);
    }
}
