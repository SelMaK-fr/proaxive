<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\EDocument;

use Envms\FluentPDO\Literal;
use Selmak\Proaxive2\Domain\Intervention\Intervention;

class EDocument
{
    public string $name = '';
    public string $filename = '';
    public string $reference = '';
    public ?float $size = null;
    public ?string $extension = null;
    public ?string $description = null;
    public int $is_online = 0;
    public ?int $interventions_id = null;
    public int $customers_id;
    public Literal $created_at;
    public Literal $updated_at;

    public function __construct()
    {
        $this->created_at = new Literal('NOW()');
        $this->updated_at = new Literal('NOW()');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): EDocument
    {
        $this->name = $name;
        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): EDocument
    {
        $this->filename = $filename;
        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): EDocument
    {
        $this->reference = $reference;
        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(?float $size): EDocument
    {
        $this->size = $size;
        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): EDocument
    {
        $this->extension = $extension;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): EDocument
    {
        $this->description = $description;
        return $this;
    }

    public function getIsOnline(): int
    {
        return $this->is_online;
    }

    public function setIsOnline(int $is_online): EDocument
    {
        $this->is_online = $is_online;
        return $this;
    }

    public function getInterventionsId(): ?int
    {
        return $this->interventions_id;
    }

    public function setInterventionsId(?int $interventions_id): EDocument
    {
        $this->interventions_id = $interventions_id;
        return $this;
    }

    public function getCustomersId(): int
    {
        return $this->customers_id;
    }

    public function setCustomersId(int $customers_id): EDocument
    {
        $this->customers_id = $customers_id;
        return $this;
    }
    public function getCreatedAt(): Literal
    {
        return $this->created_at;
    }

    public function setCreatedAt(Literal $created_at): EDocument
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): Literal
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Literal $updated_at): EDocument
    {
        $this->updated_at = $updated_at;
        return $this;
    }
}