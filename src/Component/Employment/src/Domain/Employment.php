<?php


namespace App\Component\Employment\Domain;


use EvidApp\Employment\Domain\ValueObject\Company;
use EvidApp\Employment\Domain\ValueObject\Employee;
use EvidApp\Employment\Domain\ValueObject\Job;
use EvidApp\Employment\Domain\ValueObject\WorkingHours;

class Employment
{

    public Company $company;
    public Job $job;
    public Employee $employee;
    public WorkingHours $workingHours;

    public function __construct(Company $company, Job $job, Employee $employee, WorkingHours $workingHours)
    {
        $this->company = $company;
        $this->job = $job;
        $this->employee = $employee;
        $this->workingHours = $workingHours;
    }

}