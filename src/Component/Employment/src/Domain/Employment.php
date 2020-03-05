<?php


namespace EvidApp\Employment\Domain;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use EvidApp\Employment\Domain\Event\EmploymentWasCreated;
use EvidApp\Employment\Domain\ValueObject\Company;
use EvidApp\Employment\Domain\ValueObject\Employee;
use EvidApp\Employment\Domain\ValueObject\Job;
use EvidApp\Employment\Domain\ValueObject\WorkingHours;
use Ramsey\Uuid\UuidInterface;

class Employment extends EventSourcedAggregateRoot
{

    private UuidInterface $uuid;
    public Company $company;
    public Job $job;
    public Employee $employee;
    public WorkingHours $workingHours;

    private function __construct(){}

    public static function create(
        UuidInterface $uuid,
        Company $company,
        Job $job,
        Employee $employee,
        WorkingHours $workingHours
    ): self {
        $user = new self();

        $user->apply(new EmploymentWasCreated($uuid, $credentials, DateTime::now()));

        return $user;
    }

    public function getAggregateRootId(): string
    {
        // TODO: Implement getAggregateRootId() method.
    }
}
