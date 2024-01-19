<?php
declare(strict_types=1);
namespace App\Service;

use App\Factory\MailerFactory;

class MailService
{
    public function __construct(private array $settings)
    {
    }

    public function sendMail(string $to, mixed $view, string $subject)
    {
        $mail = new MailerFactory($this->settings);
        $sendmail = $mail->createMailer();
        $sendmail->isSMTP();
        $sendmail->setFrom($this->settings['from']);
        $sendmail->addAddress($to);
        $sendmail->isHTML(true);
        $sendmail->Subject = $subject;
        $sendmail->msgHTML($view);
        $sendmail->SMTPAuth = $this->settings['SMTPAuth'];
        $sendmail->send();
    }
}