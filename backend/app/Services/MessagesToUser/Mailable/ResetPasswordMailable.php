<?php

namespace App\Services\MessagesToUser\Mailable;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPasswordMailable extends Mailable
{
  use Queueable, SerializesModels;

  protected string $link;
  protected User $user;

  /**
   * Create a new message instance.
   */
  public function __construct(string $code, User $user)
  {
    $frontUrl = env("APP_FRONTEND_LINK", "") . "/confirmation/reset-password?code=" . $code . "&email=" . $user->email;
    $this->link = '<a href="' . $frontUrl . '">Сбросить пароль</a>';
    $this->user = $user;
  }

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
    return new Content(
      view: 'email.GeneralTemplate',
      with: [
        'user' => $this->user,
        'gt_title' => 'AudioFree — Сброс пароля',
        'gt_contents' => [
          ['content' => 'Вы получили это письмо, так как был запрошен сброс пароля для вашего профиля'],
          ['content' => 'Чтобы сбросить текущий пароль, перейдите по ссылке ниже:'],
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
