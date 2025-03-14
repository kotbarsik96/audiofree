<?php

namespace App\Services\MessagesToUser\Mailable;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMailable extends MailableCustom
{
  use Queueable, SerializesModels;
  protected string $reason = 'было запрошено подтверждение адреса электронной почты';

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
    $frontUrl = env("APP_FRONTEND_LINK", "") . "/confirmation/verify-email?code=" . $this->code;

    $link = '<a href="' . $frontUrl . '">Подтвердить Email</a>';

    return new Content(
      view: 'email.GeneralTemplate',
      with: [
        'user' => auth()->user(),
        'gt_title' => 'AUDIOFREE — сброс пароля',
        'gt_contents' => [
          ['content' => 'Вы получили это письмо, так как был запрошен код подтверждения адреса эл. почты'],
          ['content' => 'Чтобы подтвердить адрес, перейдите по ссылке ниже:'],
          ['content' => $link]
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
