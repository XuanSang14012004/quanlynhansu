<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LogUserLoginHistory
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle($event)
    {
        // Xác định hành động dựa trên class của Event
        $action = null;
        if ($event instanceof Login) {
            $action = 'login';
        } elseif ($event instanceof Logout) {
            $action = 'logout';
        }

        if ($action && $event->user) {
            LoginHistory::create([
                'user_id' => $event->user->id,
                'action' => $action,
                'ip_address' => $this->request->ip(),
                'user_agent' => $this->request->userAgent(),
            ]);
        }
    }
}