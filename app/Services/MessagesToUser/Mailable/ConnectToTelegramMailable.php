<?php

namespace App\Services\MessagesToUser\Mailable;

use App\Services\StringsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ConnectToTelegramMailable extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(
    public User $user,
    public string $code,
    public string $telegramLogin
  ) {
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'AudioFree — Подключение к Telegram',
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
        'gt_title' => "AudioFree — Подключение к Telegram-аккаунту $this->telegramLogin",
        'gt_contents' => [
          ['content' => "Используйте код $this->code , чтобы привязать ваш профиль к Telegram-аккаунту $this->telegramLogin"],
          ['content' => "Осторожно! Если $this->telegramLogin не ваш Telegram, удалите это письмо"]
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
