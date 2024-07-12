<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Auth;

class SessionUser
{
    private int $id;
    private int $company_id;
    private string $pseudo;
    private string $fullname;
    private string $mail;
    private string $roles;
    private string $avatar;
    private string $auth_token;

    public function __construct(array $data) {
        $this->setId($data['id'] ?? 0);
        $this->setCompanyId($data['company_id'] ?? 0);
        $this->setPseudo($data['pseudo'] ?? '');
        $this->setFullname($data['fullname'] ?? '');
        $this->setMail($data['mail'] ?? '');
        $this->setRoles($data['roles'] ?? '');
        $this->setAvatar($data['avatar'] ?? '');
        $this->setAuthToken($data['auth_token'] ?? '');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): SessionUser
    {
        $this->id = $id;
        return $this;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): SessionUser
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): SessionUser
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): SessionUser
    {
        $this->mail = $mail;
        return $this;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): SessionUser
    {
        $this->roles = $roles;
        return $this;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): SessionUser
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getAuthToken(): string
    {
        return $this->auth_token;
    }

    public function setAuthToken(string $auth_token): SessionUser
    {
        $this->auth_token = $auth_token;
        return $this;
    }

    public function getCompanyId(): int
    {
        return $this->company_id;
    }

    public function setCompanyId(int $company_id): SessionUser
    {
        $this->company_id = $company_id;
        return $this;
    }

}