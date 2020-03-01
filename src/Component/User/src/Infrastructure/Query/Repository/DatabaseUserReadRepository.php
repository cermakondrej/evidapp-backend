<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Query\Repository;

use Assert\Assertion;
use EvidApp\User\Domain\Repository\CheckUserByEmailInterface;
use EvidApp\User\Domain\Repository\GetUserCredentialsByEmailInterface;
use EvidApp\User\Domain\ValueObject\Email;
use EvidApp\Shared\Infrastructure\Query\Repository\DatabaseRepository;
use EvidApp\User\Infrastructure\Query\Projections\UserView;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class DatabaseUserReadRepository extends DatabaseRepository implements CheckUserByEmailInterface, GetUserCredentialsByEmailInterface
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = UserView::class;
        parent::__construct($entityManager);
    }

    public function oneByUuid(UuidInterface $uuid): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $uuid->toString());

        return $this->oneOrException($qb);
    }

    public function existsEmail(Email $email): ?UuidInterface
    {
        $userId = $this->repository
            ->createQueryBuilder('user')
            ->select('user.uuid')
            ->where('user.credentials.email = :email')
            ->setParameter('email', (string)$email)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getOneOrNullResult();

        return $userId['uuid'] ?? null;
    }

    public function oneByEmail(Email $email): UserView
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());

        return $this->oneOrException($qb);
    }

    public function page(int $page, int $limit): array
    {
        Assertion::greaterThan($page, 0, 'Page needs to be bigger than 0');
        Assertion::greaterThan($limit, 0, 'Limit needs to be bigger than 0');

        // TODO implement pagination :)
        $data = $this->repository
            ->createQueryBuilder('user')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return [
            'data' => $data,
            'total' => count($data),
        ];
    }

    public function add(UserView $userRead): void
    {
        $this->register($userRead);
    }

    public function getCredentialsByEmail(Email $email): array
    {
        $user = $this->oneByEmail($email);

        return [
            $user->uuid(),
            $user->email(),
            $user->hashedPassword(),
        ];
    }
}
