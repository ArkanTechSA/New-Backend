<?php

namespace App\Services;

use App\Mail\DynamicMail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function send(
        string $to,
        string $subject,
        string|array $body,
        string $type = 'plain',
        ?string $viewPath = null,
        array $data = [],
        string $mailAccount = 'noreply',
        int $priority = 3,
        array $cc = [],
        array $bcc = [],
        array $attachments = [],
        ?string $replyTo = null
    ): bool {
        try {
            $settings = config("mail_accounts.$mailAccount");

            if (! $settings) {
                throw new \Exception("Mail account [$mailAccount] not found.");
            }

            // إعداد إعدادات المرسل
            config([
                'mail.mailers.smtp.host' => $settings['host'],
                'mail.mailers.smtp.port' => $settings['port'],
                'mail.mailers.smtp.username' => $settings['username'],
                'mail.mailers.smtp.password' => $settings['password'],
                'mail.mailers.smtp.encryption' => $settings['encryption'],
                'mail.from.address' => $settings['from']['address'],
                'mail.from.name' => $settings['from']['name'],
            ]);

            $mail = Mail::mailer($settings['driver'] ?? 'smtp')->to($to);

            if (! empty($cc)) {
                $mail->cc($cc);
            }
            if (! empty($bcc)) {
                $mail->bcc($bcc);
            }
            // إرسال باستخدام Mailable DynamicMail
            $mail->send(new DynamicMail($subject, $viewPath, $data, $type, $body, $attachments, $priority, $replyTo));

            return true;
        } catch (\Exception $e) {
            report($e);

            return false;
        }
    }
}
