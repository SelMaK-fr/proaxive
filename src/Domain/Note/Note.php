<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Note;

use Envms\FluentPDO\Literal;
use Selmak\Proaxive2\Domain\BaseDTO;

class Note extends BaseDTO
{
    private ?int $id = null;
    private ?string $title = '';
    private ?string $content = '';
    private mixed $stick = 0;
    private ?string $bgcolor = '#000';
    private ?string $txtcolor = '#fff';
    private ?int $users_id = null;
    private ?int $company_id = null;
    private Literal $created_at;
    private Literal $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Note
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Note
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Note
    {
        $this->content = $content;
        return $this;
    }

    public function getStick(): mixed
    {
        return $this->stick;
    }

    public function setStick(mixed $stick): Note
    {
        $this->stick = $stick;
        return $this;
    }

    public function getBgcolor(): ?string
    {
        return $this->bgcolor;
    }

    public function setBgcolor(?string $bgcolor): Note
    {
        $this->bgcolor = $bgcolor;
        return $this;
    }

    public function getTxtcolor(): ?string
    {
        return $this->txtcolor;
    }

    public function setTxtcolor(?string $txtcolor): Note
    {
        $this->txtcolor = $txtcolor;
        return $this;
    }

    public function getUsersId(): ?int
    {
        return $this->users_id;
    }

    public function setUsersId(?int $users_id): Note
    {
        $this->users_id = $users_id;
        return $this;
    }

    public function getCompanyId(): ?int
    {
        return $this->company_id;
    }

    public function setCompanyId(?int $company_id): Note
    {
        $this->company_id = $company_id;
        return $this;
    }

    public function getCreatedAt(): Literal
    {
        return $this->created_at;
    }

    public function setCreatedAt(Literal $created_at): Note
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): Literal
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Literal $updated_at): Note
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}
