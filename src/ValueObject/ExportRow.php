<?php

declare(strict_types=1);

namespace App\ValueObject;

use DateTimeInterface;
use JsonSerializable;

class ExportRow implements JsonSerializable
{
    /**
     * @var int
     */
    private $day;

    /** @var bool */
    private $darkRow = true;

    /**
     * @var DateTimeInterface|null
     */
    private $workStart;
    /**
     * @var DateTimeInterface|null
     */
    private $workEnd;
    /**
     * @var DateTimeInterface|null
     */
    private $breakStart;
    /**
     * @var DateTimeInterface|null
     */
    private $breakEnd;
    /**
     * @var float|null
     */
    private $hoursWorked;
    /**
     * @var string|null
     */
    private $note;


    public function __construct(int $day)
    {
        $this->day = $day;
    }


    public function setDarkRow(bool $darkRow): void
    {
        $this->darkRow = $darkRow;
    }

    public function setWorkStart(DateTimeInterface $workStart): void
    {
        $this->workStart = $workStart;
    }

    public function setWorkEnd(DateTimeInterface $workEnd): void
    {
        $this->workEnd = $workEnd;
    }

    public function setBreakStart(?DateTimeInterface $breakStart): void
    {
        $this->breakStart = $breakStart;
    }

    public function setBreakEnd(?DateTimeInterface $breakEnd): void
    {
        $this->breakEnd = $breakEnd;
    }

    public function setHoursWorked(float $hoursWorked): void
    {
        $this->hoursWorked = $hoursWorked;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }


    private function getFormatedDate(?DateTimeInterface $date): ?string
    {
        if($date !== null) {
            return $date->format('H:i');
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'day' => $this->day,
            'dark_row' => $this->darkRow,
            'work_start' => $this->getFormatedDate($this->workStart),
            'work_end' => $this->getFormatedDate($this->workEnd),
            'break_start' => $this->getFormatedDate($this->breakStart),
            'break_end' => $this->getFormatedDate($this->breakEnd),
            'hours_worked' => $this->hoursWorked,
            'note' => $this->note
        ];
    }
}