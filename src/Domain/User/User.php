<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\User;

class User
{
    private string $mail = '';
    private ?string $pseudo = null;
    private ?string $fullname = null;
    private string $password = '';
    private ?string $avatar = null;
    private ?string $addressIp = null;
    private ?string $token = null;
    private ?\DateTime $resetAt = null;
    private ?string $resetCode = null;
    private array|string $roles = [];
    private \DateTime $createdAt;
    private \DateTime $updatedAt;
    private ?string $authToken = null;
    private int|string $companyId = '';

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): User
    {
        $this->mail = $mail;
        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): User
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): User
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): User
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getAddressIp(): ?string
    {
        return $this->addressIp;
    }

    public function setAddressIp(?string $addressIp): User
    {
        $this->addressIp = $addressIp;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): User
    {
        $this->token = $token;
        return $this;
    }

    public function getResetAt(): \DateTime
    {
        return $this->resetAt;
    }

    public function setResetAt(?\DateTime $resetAt): User
    {
        $this->resetAt = $resetAt;
        return $this;
    }

    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    public function setResetCode(?string $resetCode): User
    {
        $this->resetCode = $resetCode;
        return $this;
    }

    public function getRoles(): array|string
    {
        return $this->roles;
    }

    public function setRoles(array|string $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    public function setAuthToken(?string $authToken): User
    {
        $this->authToken = $authToken;
        return $this;
    }

    public function getCompanyId(): int|string
    {
        return $this->companyId;
    }

    public function setCompanyId(int|string $companyId): User
    {
        $this->companyId = $companyId;
        return $this;
    }


}
