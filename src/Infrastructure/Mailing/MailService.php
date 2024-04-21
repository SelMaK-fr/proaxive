<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Mailing;

use Selmak\Proaxive2\Infrastructure\Mailing\Factory\MailerFactory;

class MailService
{
    public function __construct(private array $settings)
    {
    }

    public function sendMail(string $to, mixed $view, string $subject): void
    {
        $mail = new MailerFactory($this->settings);
        try{
        $sendmail = $mail->createMailer();
        $sendmail->setFrom($this->settings['from']);
        $sendmail->addAddress($to);
        $sendmail->isHTML(true);
        $sendmail->Subject = $subject;
        $sendmail->msgHTML($view);
        $sendmail->send();
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$sendmail->ErrorInfo}";
        }
    }

    public function sendMailWithAttachment(string $to, mixed $view, string $subject, mixed $attachment): void
    {
        $mail = new MailerFactory($this->settings);
        try{
            $sendmail = $mail->createMailer();
            $sendmail->setFrom($this->settings['from']);
            $sendmail->addAddress($to);
            $sendmail->isHTML(true);
            $sendmail->Subject = $subject;
            $sendmail->msgHTML($view);
            $sendmail->addAttachment($attachment);
            $sendmail->send();
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$sendmail->ErrorInfo}";
        }
    }
}