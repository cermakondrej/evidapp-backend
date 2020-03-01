<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Doctrine;

use EvidApp\User\Domain\ValueObject\Auth\HashedPassword;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class HashedPasswordType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?HashedPassword
    {
        $value = parent::convertToPHPValue($value, $platform);

        if (empty($value)) {
            return null;
        }

        return HashedPassword::fromHash($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        /** @var HashedPassword $value */
        $value = $value->toString();

        return parent::convertToDatabaseValue($value, $platform);
    }
}