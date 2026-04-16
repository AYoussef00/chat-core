<?php

namespace App\Http\Controllers;

use App\Models\FacebookPageConnection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $connectedPages = [];

        if ($user) {
            $connectedPages = FacebookPageConnection::query()
                ->where('user_id', $user->id)
                ->orderBy('page_name')
                ->get(['id', 'page_id', 'page_name', 'status', 'updated_at'])
                ->toArray();
        }

        return Inertia::render('Dashboard', [
            'connectedPages' => $connectedPages,
        ]);
    }
}

