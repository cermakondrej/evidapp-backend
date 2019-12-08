<?php declare

(strict_types = 1);

namespace App\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;

class EntityNormalizer extends ObjectNormalizer
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(
        EntityManagerInterface $em,
        ?ClassMetadataFactoryInterface $classMetadataFactory = null,
        ?NameConverterInterface $nameConverter = null,
        ?PropertyAccessorInterface $propertyAccessor = null,
        ?PropertyTypeExtractorInterface $propertyTypeExtractor = null
    ) {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);
        $this->em = $em;
    }
    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return strpos($type, 'App\\Entity\\') === 0 && (is_numeric($data) || is_string($data));
    }
    /**
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = []) : ?object
    {
        return $this->em->find($class, $data);
    }
}
