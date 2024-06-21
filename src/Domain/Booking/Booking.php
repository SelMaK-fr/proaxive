<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Booking;

use Phinx\Util\Literal;

class Booking
{
    public string $start_date = '';
    public string $end_date = '';
    public string $start_time = '';
    public string $end_time = '';
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
    }

    public function getStartDate(): string
    {
        return $this->start_date;
    }

    public function setStartDate(string $start_date): Booking
    {
        $this->start_date = $start_date;
        return $this;
    }

    public function getEndDate(): string
    {
        return $this->end_date;
    }

    public function setEndDate(string $end_date): Booking
    {
        $this->end_date = $end_date;
        return $this;
    }

    public function getStartTime(): string
    {
        return $this->start_time;
    }

    public function setStartTime(string $start_time): Booking
    {
        $this->start_time = $start_time;
        return $this;
    }

    public function getEndTime(): string
    {
        return $this->end_time;
    }

    public function setEndTime(string $end_time): Booking
    {
        $this->end_time = $end_time;
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