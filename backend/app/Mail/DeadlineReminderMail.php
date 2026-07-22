<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Scholarship;
use App\Models\User;

class DeadlineReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $scholarship;
    public $daysLeft;
    public $userName;
    //recoit les données et les assignes aux propriétés
    public function __construct(Scholarship $scholarship, int $daysLeft)
    {
        $this->scholarship = $scholarship;
        $this->daysLeft = $daysLeft;
        $this->userName = $userName;
    }

    //enveloppe de l'email
    public function envelope(): Envelope
    {
        return new Envelope(subject: "Date limite bientot : {$this->scholarship->title}");
    }

    //contenu de l'email
    public function content(): Content
    {
        return new Content(view: 'emails.deadline-reminder');
    }
}
