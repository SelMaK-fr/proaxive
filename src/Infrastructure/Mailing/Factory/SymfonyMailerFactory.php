<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Mailing\Factory;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class SymfonyMailerFactory
{

    public function __construct(private readonly array $setting)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendMail(string $to, mixed $view, string $subject): void
    {
        $transport = Transport::fromDsn($this->setting['dsn']);
        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from($this->setting['from'])
            ->to($to)
            ->subject('Time for Symfony Mailer!')
            ->html($view);
            ;
        $mailer->send($email);
    }
}