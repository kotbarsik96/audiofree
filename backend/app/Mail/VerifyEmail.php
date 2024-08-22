<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
  use Queueable, SerializesModels;

  protected string $link;
  protected string $reason = 'было запрошено подтверждение адреса электронной почты';

  /**
   * Create a new message instance.
   */
  public function __construct(string $code)
  {
    $frontUrl = env("APP_FRONTEND_LINK", "") . "/verify-email?code=" . $code;

    $this->link = '<a href="' . $frontUrl . '">Подтвердить Email</a>';
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'AudioFree — Подтверждение Email',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'email.GeneralTemplate',
      with: [
        'user' => auth()->user(),
        'gt_title' => 'AUDIOFREE — сброс пароля',
        'gt_contents' => [
          ['content' => 'Вы получили это письмо, так как был запрошен код подтверждения адреса эл. почты'],
          ['content' => 'Чтобы подтвердить адрес, перейдите по ссылке ниже:'],
          ['content' => $this->link]
        ]
      ]
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, \Illuminate\Mail\Mailables\Attachment>
   */
  public function attachments(): array
  {
    return [];
  }
}
