<?php


namespace App\Service\Export;


use App\DTO\ExportOutput;
use App\Entity\WorkExport;

interface WorkExporterInterface
{

    public function createExport(WorkExport $export): ExportOutput;
}