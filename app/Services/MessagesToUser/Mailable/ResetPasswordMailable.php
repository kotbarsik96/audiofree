<?php

namespace App\Services\MessagesToUser\Mailable;

use App\Services\StringsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailable extends MailableCustom
{
  use Queueable, SerializesModels;

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'AudioFree — Сброс пароля',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    if ($this->user->email) {
      $frontUrl = StringsService::resetLink($this->code, $this->user->email);
      $link = "<a href=\"$frontUrl\">Сбросить пароль</a>";
    }

    return new Content(
      view: 'email.GeneralTemplate',
      with: [
        'user' => $this->user,
        'gt_title' => 'AudioFree — Сброс пароля',
        'gt_contents' => [
          ['content' => 'Вы получили это письмо, так как был запрошен сброс пароля для вашего профиля'],
          ['content' => 'Чтобы сбросить текущий пароль, перейдите по ссылке ниже:'],
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
