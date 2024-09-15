<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\InterventionPicture;

use Envms\FluentPDO\Literal;
use Selmak\Proaxive2\Domain\BaseDTO;

class InterventionPicture extends BaseDTO
{
    private ?int $id = null;
    private ?string $name = '';
    private ?string $filename = null;
    private ?int $filesize = null;
    private ?int $picture_order = 1;
    private ?string $description = null;
    private ?int $interventions_id = null;
    private int $is_online = 0;
    private Literal $created_at;
    private Literal $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): InterventionPicture
    {
        $this->id = $id;
        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): InterventionPicture
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFilesize(): ?int
    {
        return $this->filesize;
    }

    public function setFilesize(?int $filesize): InterventionPicture
    {
        $this->filesize = $filesize;
        return $this;
    }

    public function getPictureOrder(): ?int
    {
        return $this->picture_order;
    }

    public function setPictureOrder(?int $picture_order): InterventionPicture
    {
        $this->picture_order = $picture_order;
        return $this;
    }

    public function getInterventionsId(): ?int
    {
        return $this->interventions_id;
    }

    public function setInterventionsId(?int $interventions_id): InterventionPicture
    {
        $this->interventions_id = $interventions_id;
        return $this;
    }

    public function getCreatedAt(): Literal
    {
        return $this->created_at;
    }

    public function setCreatedAt(Literal $created_at): InterventionPicture
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): Literal
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Literal $updated_at): InterventionPicture
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): InterventionPicture
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): InterventionPicture
    {
        $this->description = $description;
        return $this;
    }

    public function getIsOnline(): ?int
    {
        return $this->is_online;
    }

    public function setIsOnline(?int $is_online): InterventionPicture
    {
        $this->is_online = $is_online;
        return $this;
    }

}
