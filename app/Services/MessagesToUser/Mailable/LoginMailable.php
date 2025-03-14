<?php

namespace App\Services\MessagesToUser\Mailable;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class LoginMailable extends MailableCustom
{
  use Queueable, SerializesModels;

  protected string $reason = 'код для входа в профиль';

  public function getLink()
  {
    $link = env('APP_FRONTEND_LINK');
    $linkWithoutHttps = preg_replace('/http(s?):\/\//', '', $link);
    return "<a href=\"$link\">$linkWithoutHttps</a>";
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'AudioFree — Код авторизации',
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
        'user' => $this->user,
        'gt_title' => 'AUDIOFREE — Код авторизации',
        'gt_contents' => [
          ['content' => 'Для авторизации на сайте ' . $this->getLink()],
          ['content' => "вы можете использовать код $this->code"],
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
