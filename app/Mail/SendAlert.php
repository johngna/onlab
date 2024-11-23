<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $alarm;
    /**
     * Create a new message instance.
     */
    public function __construct($alarm)
    {
        $this->alarm = $alarm;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alarme de Equipamento',
        );
    }

    public function build()
    {
        return $this->subject('Equipamento com alarme')
                    ->markdown('emails.alert')
                    ->with([
                        'alarme' => $this->alarm,
                    ]);
    }
}
