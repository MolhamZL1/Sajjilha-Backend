<?php

namespace App\Listeners;

use App\Events\MobileVerifyByCode;
use App\Mail\sendActiveCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class MobileVerifyByCodeFired
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
    public function handle(MobileVerifyByCode $event): void
    {
        Mail::to($event->user->email)->send(new sendActiveCode(__('main.activeAccount',['type'=>__('main.mobile')]),__('main.msg_code_mobile',['code'=>$event->user->mobile_code,'name'=>$event->user->name])));

    }
}

