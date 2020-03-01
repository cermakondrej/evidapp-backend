<?php

declare(strict_types=1);

namespace EvidApp\User\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use EvidApp\User\Domain\ValueObject\Email;

class EmailType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        $value = parent::convertToPHPValue($value, $platform);

        if (empty($value)) {
            return null;
        }

        return Email::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        $value = $value->toString();

        return parent::convertToDatabaseValue($value, $platform);
    }
}
