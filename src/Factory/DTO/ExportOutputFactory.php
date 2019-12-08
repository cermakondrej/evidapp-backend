<?php

declare(strict_types=1);

namespace App\Factory\DTO;

use App\DTO\ExportOutput;
use App\Entity\WorkExport;

class ExportOutputFactory
{

    public function create(WorkExport $export): ExportOutput
    {
        return new ExportOutput(
            $export->getEmployee()->getFullName(),
            $export->getJobName(),
            $export->getWork()->getWorkload(),
            $export->getWork()->getCompany()->getName(),
            $export->getMonth(),
            $export->getYear()
        );
    }
}
