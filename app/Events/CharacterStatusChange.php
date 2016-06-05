<?php

namespace App\Events;

use App\Character;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CharacterStatusChange extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Character which has had it's status changed.
     *
     * @var Character
     */
    public $character;

    /**
     * Holds the old status, before the current check.
     *
     * @var string
     */
    public $old_status;

    /**
     * User who owns the character.
     *
     * @var App\User
     */
    protected $user;

    /**
     * Create a new event instance.
     */
    public function __construct($old_status, Character $character)
    {
        $this->old_status = $old_status;
        $this->character = $character;
        $this->user = $character->user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user.' . $this->user->id];
    }

    /**
     * Get the user instance but only to other server-side apps.
     *
     * @return App\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
