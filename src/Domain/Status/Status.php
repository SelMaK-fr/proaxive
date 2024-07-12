<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Status;

use Selmak\Proaxive2\Domain\BaseDTO;

class Status extends BaseDTO
{
    private ?int $id = null;
    private string $name;
    private string $color;
    private string $color_txt;
    private string $description;
    private int $fixed;

    public function __construct(?array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->color = $data['color'];
        $this->color_txt = $data['color_txt'];
        $this->description = $data['description'];
        $this->fixed = (int)$data['fixed'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Status
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Status
    {
        $this->name = $name;
        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): Status
    {
        $this->color = $color;
        return $this;
    }

    public function getColorTxt(): string
    {
        return $this->color_txt;
    }

    public function setColorTxt(string $color_txt): Status
    {
        $this->color_txt = $color_txt;
        return $this;
    }

    public function getFixed(): int
    {
        return $this->fixed;
    }

    public function setFixed(int $fixed): Status
    {
        $this->fixed = $fixed;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Status
    {
        $this->description = $description;
        return $this;
    }

}