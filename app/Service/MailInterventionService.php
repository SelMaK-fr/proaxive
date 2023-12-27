<?php
declare(strict_types=1);
namespace App\Service;

use App\Factory\MailerFactory;

class MailInterventionService
{

    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function sendMailStart(string $to, mixed $view)
    {
        $mail = new MailerFactory($this->settings);
        $sendmail = $mail->createMailer();
        $sendmail->setFrom($this->settings['from']);
        $sendmail->addAddress($to);
        $sendmail->isHTML(true);
        $sendmail->Subject = "Début d'intervention";
        $sendmail->msgHTML($view);
        $sendmail->send();
    }
}