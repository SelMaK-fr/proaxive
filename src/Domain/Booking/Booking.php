<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Booking;

use Phinx\Util\Literal;

class Booking
{
    public string $begin_at = '';
    public string $end_at = '';
    public string $title = 'New Booking';
    public string $subtitle = '';
    public string|null $description = null;
    public string|null $backgroundColor = null;
    public string|null $textColor = null;
    public int $allDay = 0;
    public string|null $component = 'Intervention';
    public \Envms\FluentPDO\Literal $created_at;

    public function __construct()
    {
        $this->created_at = new \Envms\FluentPDO\Literal('NOW');
        $this->updated_at = new \Envms\FluentPDO\Literal('NOW');
    }

    public function getBeginAt(): string
    {
        return $this->begin_at;
    }

    public function setBeginAt(string $begin_at): Booking
    {
        $this->begin_at = $begin_at;
        return $this;
    }

    public function getEndAt(): string
    {
        return $this->end_at;
    }

    public function setEndAt(string $end_at): Booking
    {
        $this->end_at = $end_at;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Booking
    {
        $this->title = $title;
        return $this;
    }

    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): Booking
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Booking
    {
        $this->description = $description;
        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): Booking
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): Booking
    {
        $this->textColor = $textColor;
        return $this;
    }

    public function isAllDay(): int
    {
        return $this->allDay;
    }

    public function setAllDay(int $allDay): Booking
    {
        $this->allDay = $allDay;
        return $this;
    }

    public function getComponent(): ?string
    {
        return $this->component;
    }

    public function setComponent(?string $component): Booking
    {
        $this->component = $component;
        return $this;
    }

    public function getCreatedAt(): \Envms\FluentPDO\Literal
    {
        return $this->created_at;
    }

    public function setCreatedAt(\Envms\FluentPDO\Literal $created_at): Booking
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): \Envms\FluentPDO\Literal
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\Envms\FluentPDO\Literal $updated_at): Booking
    {
        $this->updated_at = $updated_at;
        return $this;
    }

}