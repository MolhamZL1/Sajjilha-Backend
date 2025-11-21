<?php

namespace App\Listeners;

use App\Events\EmailVerifyByCode;
use App\Mail\sendActiveCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use PharIo\Manifest\Email;

class EmailVerifyByCodeFired
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmailVerifyByCode $event): void
    {
        //dd($event);
        Mail::to($event->user->email)->send(new sendActiveCode(__('main.activeAccount', ['type' => __('main.email')]), __('main.msg_code_email', ['code' => $event->user->email_code, 'name' => $event->user->name])));
    }
}
