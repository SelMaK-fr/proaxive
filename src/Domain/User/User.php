<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\User;

use Envms\FluentPDO\Literal;
use Selmak\Proaxive2\Domain\BaseDTO;

class User extends BaseDTO
{
    private ?int $id;
    private string $mail;
    private ?string $pseudo;
    private ?string $fullname;
    private ?string $password;
    private ?string $avatar;
    private ?string $address_ip;
    private ?string $token;
    private ?Literal $reset_at;
    private ?string $reset_code;
    private array|string $roles;
    private Literal|string $created_at;
    private Literal|string $updated_at;
    private ?string $auth_token;
    private int $company_id;
    private ?string $confirm_at;

    public function __construct(?array $data)
    {
        $this->id = $data['id'];
        $this->mail = $data['mail'];
        $this->pseudo = $data['pseudo'];
        $this->fullname = $data['fullname'];
        $this->password = $data['password'];
        $this->avatar = $data['avatar'];
        $this->address_ip = $data['address_ip'];
        $this->token = $data['token'];
        $this->reset_at = $data['reset_at'];
        $this->reset_code = $data['reset_code'];
        $this->roles = $data['roles'];
        $this->created_at = new Literal('NOW()');
        $this->updated_at = new Literal('NOW()');
        $this->auth_token = $data['auth_token'];
        $this->company_id = (int)$data['company_id'];
        $this->confirm_at = $data['confirm_at'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): self
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getAddressIp(): ?string
    {
        return $this->address_ip;
    }

    public function setAddressIp(?string $addressIp): self
    {
        $this->address_ip = $addressIp;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getResetAt(): \DateTime
    {
        return $this->reset_at;
    }

    public function setResetAt(?Literal $resetAt): self
    {
        $this->reset_at = $resetAt;
        return $this;
    }

    public function getResetCode(): ?string
    {
        return $this->reset_code;
    }

    public function setResetCode(?string $resetCode): self
    {
        $this->reset_code = $resetCode;
        return $this;
    }

    public function getRoles(): array|string
    {
        return $this->roles;
    }

    public function setRoles(array|string $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getCreatedAt(): Literal|string
    {
        return $this->created_at;
    }

    public function setCreatedAt(Literal|string $createdAt): self
    {
        if(is_string($createdAt)) {
            $createdAt = new Literal($createdAt);
        }
        $this->created_at = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): Literal|string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Literal|string $updatedAt): self
    {
        if(is_string($updatedAt)) {
            $updatedAt = new Literal($updatedAt);
        }
        $this->updated_at = $updatedAt;
        return $this;
    }

    public function getAuthToken(): ?string
    {
        return $this->auth_token;
    }

    public function setAuthToken(?string $authToken): self
    {
        $this->auth_token = $authToken;
        return $this;
    }

    public function getCompanyId(): int
    {
        return $this->company_id;
    }

    public function setCompanyId(int $companyId): self
    {
        $this->company_id = $companyId;
        return $this;
    }

    public function getConfirmAt(): ?string
    {
        return $this->confirm_at;
    }

    public function setConfirmAt(?string $confirm_at): self
    {
        $this->confirm_at = $confirm_at;
        return $this;
    }
}
