<?php
declare(strict_types=1);
namespace App\Factory;

use PHPMailer\PHPMailer\PHPMailer;

final class MailerFactory
{

    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function createMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        // Server setting
        $mail->SMTPDebug = $this->settings['debug'];
        $mail->isSMTP();
        $mail->Host = $this->settings['host'];
        $mail->SMTPAuth = (bool)$this->settings['auth'];
        $mail->Username = $this->settings['username'];
        $mail->Password = $this->settings['password'];
        $mail->SMTPSecure = $this->settings['password'];
        $mail->Port = (int)$this->settings['port'];
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($this->settings['from']);

        return $mail;
    }
}