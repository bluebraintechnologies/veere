<?php

namespace App\Listeners;

use App\Events\OrderPlaceEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use App\Mail\UserOrder;
use App\Models\User;
use App\Models\Transaction;

class OrderEmallToCustomer
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
     * @param  \App\Events\OrderPlaceEvent  $event
     * @return void
     */
    public function handle(OrderPlaceEvent $event)
    {
        //$event->order->id
        $order = Transaction::find($event->order->id);
        $data['order'] = $order;
        $user = User::find($order->contact_id);
        $user['name'] = ucwords($user->name);
        $data['user'] = $user;
        $email = $user->email;
        Mail::to($email)->send(new UserOrder($data));
    }
}
