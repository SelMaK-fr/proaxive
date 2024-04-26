<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\User;

class User
{
    public string $mail = '';
    public ?string $pseudo = null;
    public ?string $fullname = null;
    public string $password = '';
    public ?string $avatar = null;
    public ?string $address_ip = null;
    public ?string $token = null;
    public ?\DateTime $reset_at = null;
    public ?string $reset_code = null;
    public array|string $roles = [];
    public \DateTime $created_at;
    public \DateTime $updated_at;
    public ?string $auth_token = null;
    public int|string $company_id = '';
    public ?int $confirm_at = null;

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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
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

    public function setResetAt(?\DateTime $resetAt): self
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

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->created_at = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
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

    public function getCompanyId(): int|string
    {
        return $this->company_id;
    }

    public function setCompanyId(int|string $companyId): self
    {
        $this->company_id = $companyId;
        return $this;
    }

    public function getConfirmAt(): ?int
    {
        return $this->confirm_at;
    }

    public function setConfirmAt(?int $confirm_at): self
    {
        $this->confirm_at = $confirm_at;
        return $this;
    }



}
