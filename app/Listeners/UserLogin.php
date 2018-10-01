<?php

namespace App\Listeners;

use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class UserLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Login $event
     *
     * @return void
     */
    public function handle(Login $event)
    {
        // Update user last logged in time.
        $event->user->update(['login_at' => Carbon::now()]);
    }
}
