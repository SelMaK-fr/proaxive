<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Brand;

class Brand
{
    private string $name = '';
    public ?string $logo_link = null;
    private string $slug = '';
    public ?string $logo_file = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Brand
    {
        $this->name = $name;
        return $this;
    }

    public function getLogoLink(): ?string
    {
        return $this->logo_link;
    }

    public function setLogoLink(?string $logo_link): self
    {
        $this->logo_link = $logo_link;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getLogoFile(): ?string
    {
        return $this->logo_file;
    }

    public function setLogoFile(?string $logo_file): self
    {
        $this->logo_file = $logo_file;
        return $this;
    }


}