<?php


namespace EvidApp\Employment\Domain\Event;

use App\Entity\Work;
use Assert\Assertion;
use Broadway\Serializer\Serializable;
use EvidApp\Employment\Domain\ValueObject\Company;
use EvidApp\Employment\Domain\ValueObject\Employee;
use EvidApp\Employment\Domain\ValueObject\Job;
use EvidApp\Employment\Domain\ValueObject\WorkingHours;
use EvidApp\Shared\Domain\ValueObject\DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class EmploymentWasCreated implements Serializable
{

    public UuidInterface $uuid;
    public Company $company;
    public Job $job;
    public Employee $employee;
    public WorkingHours $workingHours;
    public DateTime $createdAt;

    public function __construct(
        UuidInterface $uuid,
        Company $company,
        Job $job,
        Employee $employee,
        WorkingHours $workingHours,
        DateTime $createdAt
    ) {
        $this->uuid = $uuid;
        $this->company = $company;
        $this->job = $job;
        $this->employee = $employee;
        $this->workingHours = $workingHours;
        $this->createdAt = $createdAt;
    }

    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'company');
        Assertion::keyExists($data, 'job');
        Assertion::keyExists($data, 'employee');
        Assertion::keyExists($data, 'working_hours');

        return new self(
            Uuid::fromString($data['uuid']),
            new Company(),
            new Job(),
            new Employee(),
            new WorkingHours,
            DateTime::fromString($data['created_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'credentials' => [
                'email' => $this->credentials->getEmail()->toString(),
                'password' => (string) $this->credentials->getPassword()->toString()
            ],
            'created_at' => $this->createdAt->toString(),
        ];
    }
}
