<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Customer;

use Envms\FluentPDO\Literal;
use Selmak\Proaxive2\Domain\BaseDTO;

class CustomerFastDTO extends BaseDTO
{
    private ?int $id = null;
    private string $mail = '';
    private string $firstname;
    private string $lastname;
    private string $fullname;
    private string $login_id;
    private int $activated = 0;
    private int $is_draft = 1;
    private Literal $created_at;
    private Literal $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): CustomerFastDTO
    {
        $this->id = $id;
        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): CustomerFastDTO
    {
        $this->mail = $mail;
        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): CustomerFastDTO
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): CustomerFastDTO
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFullname(): string
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    public function setFullname($fullname): CustomerFastDTO
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getLoginId(): string
    {
        return $this->login_id;
    }

    public function setLoginId(string $login_id): CustomerFastDTO
    {
        $this->login_id = $login_id;
        return $this;
    }

    public function getCreatedAt(): Literal
    {
        return $this->created_at;
    }

    public function setCreatedAt(Literal $created_at): CustomerFastDTO
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): Literal
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Literal $updated_at): CustomerFastDTO
    {
        $this->updated_at = $updated_at;
        return $this;
    }

}