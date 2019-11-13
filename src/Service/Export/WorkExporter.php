<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\WorkExport;
use App\ValueObject\ExportOutput;

interface WorkExporter
{
    public function createExport(WorkExport $export): ExportOutput;
}