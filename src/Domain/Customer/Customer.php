<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Customer;

class Customer
{
    private string $mail;

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): Customer
    {
        $this->mail = $mail;
        return $this;
    }

}