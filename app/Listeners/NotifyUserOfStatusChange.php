<?php

namespace App\Listeners;

use App\Events\CharacterStatusChange;
use App\Transmission;
use Mail;

class NotifyUserOfStatusChange
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
     *
     * @param CharacterStatusChange $event
     */
    public function handle(CharacterStatusChange $event)
    {
        $old_status = $event->old_status;
        $user = $event->getUser();
        $character = $event->character;

        if ($user->frequency === '-1') {
            return;
        }

        if ($character->status !== $old_status) {
            foreach ($user->notifiers as $notifier) {
                Transmission::create([
                    'notifier_id' => $notifier->id,
                    'status'      => $character->status,
                ]);
                Mail::raw("{$character->name} is {$character->status}!", function ($message) use ($notifier) {
                    $message->to($notifier->value);
                    $message->subject('EVE Check - Character Update');
                });
            }
        }
    }
}
