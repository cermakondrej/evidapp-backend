<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeInterface;
use JsonSerializable;

class ExportRow implements JsonSerializable
{
    /** @var int     */
    private $day;

    /** @var bool */
    private $darkRow = true;

    /** @var DateTimeInterface|null */
    private $workStart;

    /** @var DateTimeInterface|null */
    private $workEnd;

    /** @var DateTimeInterface|null */
    private $breakStart;

    /** @var DateTimeInterface|null */
    private $breakEnd;

    /** @var float|null */
    private $hoursWorked;

    /** @var string|null */
    private $note;

    public function __construct(
        int $day,
        DateTimeInterface $workStart = null,
        DateTimeInterface $workEnd = null,
        DateTimeInterface $breakStart = null,
        DateTimeInterface $breakEnd = null
    ) {
        $this->day = $day;
        $this->workStart = $workStart;
        $this->workEnd = $workEnd;
        $this->breakStart = $breakStart;
        $this->breakEnd = $breakEnd;
    }


    public function setDarkRow(bool $darkRow): void
    {
        $this->darkRow = $darkRow;
    }


    public function setHoursWorked(float $hoursWorked): void
    {
        $this->hoursWorked = $hoursWorked;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }


    private function getFormattedDate(?DateTimeInterface $date): ?string
    {
        if ($date !== null) {
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
            'work_start' => $this->getFormattedDate($this->workStart),
            'work_end' => $this->getFormattedDate($this->workEnd),
            'break_start' => $this->getFormattedDate($this->breakStart),
            'break_end' => $this->getFormattedDate($this->breakEnd),
            'hours_worked' => $this->hoursWorked,
            'note' => $this->note
        ];
    }
}
