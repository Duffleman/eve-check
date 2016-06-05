<?php

namespace App\Console\Commands;

use App\Character;
use App\Transmission;
use Illuminate\Console\Command;
use Mail;

class NotifyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users of their character status';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $characters = Character::where('status', 'offline')->get();

        foreach ($characters as $character) {
            if ($character->status === 'online') {
                break; // No one cares if a character is online.
            }

            $user = $character->user;

            if ($user->frequency === '-1') {
                \Log::info("{$user->name} has it set to not be contacted. Moving on...");
                break;
            }

            if ($user->frequency === '1') {
                \Log::info("{$user->name} has it set to only be contacted once, moving on...");
                break;
            }

            foreach ($user->notifiers as $notifier) {
                \Log::info("{$user->name} has it set to be contacted every {$user->frequency} minutes.");
                $last_transmission = Transmission::where('notifier_id', $notifier->id)
                    ->where('status', 'offline')
                    ->limit(1)
                    ->orderBy('created_at', 'DESC')
                    ->get();
                $last_transmission = $last_transmission[0];

                if ($last_transmission->created_at->diffInMinutes() < $user->frequency) {
                    \Log::info("{$user->name} was contacted {$last_transmission->created_at->diffInMinutes()} minutes ago. Moving on...");
                    break;
                }

                \Log::info("{$user->name} was contacted {$last_transmission->created_at->diffInMinutes()} minutes ago. Sending new notification!");
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
