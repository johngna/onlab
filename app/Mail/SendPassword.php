<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $senha;
    public $email;
    /**
     * Create a new message instance.
     */
    public function __construct($senha, $email)
    {
        $this->senha = $senha;
        $this->email = $email;
    }



    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Onlab - Dados de acesso',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Seus Dados de Acesso')
                    ->markdown('emails.onboarding')
                    ->with([
                        'senha' => $this->senha,
                        'email' => $this->email,
                        'url' => 'http://sistema.onlab.eng.br',
                    ]);
    }



}
