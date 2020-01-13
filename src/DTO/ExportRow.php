<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeInterface;
use JMS\Serializer\Annotation\Type;


class ExportRow
{
    /** @var int     */
    private $day;

    /** @var bool */
    private $darkRow = true;

    /**
     * @Type("DateTime<'H:i'>")
     * @var DateTimeInterface|null
     */
    private $workStart;

    /**
     * @Type("DateTime<'H:i'>")
     * @var DateTimeInterface|null
     */
    private $workEnd;

    /**
     * @Type("DateTime<'H:i'>")
     * @var DateTimeInterface|null
     */
    private $breakStart;

    /**
     * @Type("DateTime<'H:i'>")
     * @var DateTimeInterface|null
     */
    private $breakEnd;

    /** @var string */
    private $hoursWorked = "";

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


    public function setHoursWorked(string $hoursWorked): void
    {
        $this->hoursWorked = $hoursWorked;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

}
