<?php

namespace App\Console\Commands;

use App\Models\Favorite;
use App\Models\Notification;
use App\Mail\DeadlineReminderMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckDeadlineReminders extends Command
{
    //signature utilisé pour lancé la commande (de facon automatique)
    protected $signature = 'notify:deadlines';
    protected $description = 'Vérifie les deadlines des bourses favorites et envoie des rappels email';

    public function handle()
    {
        $this->info('Vérifications des deadlines');

        $today = Carbon::today(); //retourne la date d'aujourd'hui (jour)
        $count = 0;

        Favorite::with(['user', 'scholarship'])
            ->whereHas('scholarship', function ($query){
                $query->where('deadline', '>=', now());
            })

            //chunk c'est pour l'optimisation de la mémoire afin de ne pas charger plusieurs paquets en meme temps 
            //le & devant $count modifie la variable originale sinon le compteur resterait à 0 à la fin
            
            ->chunk(20, function ($favorites) use ($today, &$count){

                foreach ($favorites as $favorite){
                    $scholarship = $favorite->scholarship;
                    $user = $favorite->user;

                    if(!$scholarship || !$user){
                        continue;
                    }

                    $deadline = Carbon::parse($scholarship->deadline);
                    $daysLeft = $today->diffInDays($deadline, false);
                    $reminderDays = config('notifications.reminder_days', [30, 14, 7, 3, 1]);

                    if (!in_array($daysLeft, $reminderDays)){
                        continue;
                    }

                    $exists = Notification::where('user_id', $user->id)
                        ->where('scholarship_id', $scholarship->id)
                        ->where('type', 'deadline_reminder')
                        ->whereDate('sent_at', $today)
                        ->exists();

                    if($exists){
                        continue;
                    }

                    $title = match($daysLeft) {
                        30 => "Plus que 30 jours !",
                        14 => "Plus que 14 jours !",
                        7 => "Plus que 7 jours !",
                        3 => "Plus que 3 jours !",
                        1 => "Dernier jour !",
                        default => "Date limite bientot !"
                    };

                    $message = "La bourse \"{$scholarship->title}\"ferme dans {$daysLeft} jour" . ($daysLeft > 1 ? 's' : '') . ". Ne manquez pas cette opportunité !";

                    //sauvegarde en base
                    Notification::create([
                        'user_id' => $user->id,
                        'scholarship_id' => $scholarship->id,
                        'type' => 'deadline_reminder',
                        'title' => $title,
                        'message' => $message,
                        'link' => "/scholarships/{$scholarship->id}",
                        'sent_at' => now(),
                    ]);

                    try{
                        Mail::to($user->email)->send(new DeadlineReminderMail($scholarship, $daysLeft));
                        $count++;
                    } catch (\Exception $e) {
                        \Log::error("Erreur envoi email à {$user->email} : " . $e->getMessage());
                    }
                }
            });
                
        $this->info("{$count} rappels envoyés.");
    }
}
