<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\WorkExport;
use App\DTO\ExportOutput;
use App\Factory\DTO\ExportOutputFactory;
use App\Factory\DTO\ExportRowFactory;
use App\Factory\ValueObject\AbsenceFactory;
use App\Factory\ValueObject\DayInMonthFactory;
use App\Factory\ValueObject\MonthFactory;

class EmployeeWorkExporter implements WorkExporterInterface
{
    /** @var AbsenceFactory */
    private $absenceFactory;

    /** @var DayInMonthFactory */
    private $dayInMonthFactory;

    /** @var MonthFactory */
    private $monthFactory;

    /** @var ExportRowFactory */
    private $exportRowFactory;

    /** @var ExportOutputFactory */
    private $exportOutputFactory;

    public function __construct(
        AbsenceFactory $absenceFactory,
        DayInMonthFactory $dayInMonthFactory,
        MonthFactory $monthFactory,
        ExportRowFactory $exportRowFactory,
        ExportOutputFactory $exportOutputFactory
    ) {
        $this->absenceFactory = $absenceFactory;
        $this->dayInMonthFactory = $dayInMonthFactory;
        $this->monthFactory = $monthFactory;
        $this->exportRowFactory = $exportRowFactory;
        $this->exportOutputFactory = $exportOutputFactory;
    }

    public function createExport(WorkExport $export): ExportOutput
    {
        $output = $this->exportOutputFactory->create($export);

        $exportRows = [];
        $month = $this->monthFactory->create($export->getMonth(), $export->getYear());

        $output->setWorkHours($month->getNumberOfWorkingDays() * $export->getWork()->getWorkload() * 8);

        return $output;
    }
}
