<?php
namespace Vivait\DelayedEventBundle\Normalizer;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DoctrineORMNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function normalize($data, $format = null, array $context = array())
    {
        $class = get_class($data);

        $entity_manager = $this->doctrine->getManagerForClass($class);

        /* @var UnitOfWork $uow */
        $uow = $entity_manager->getUnitOfWork();
        $id = $uow->getSingleIdentifierValue($data);

        return [
            $class,
            $id
        ];
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->doctrine->getRepository($class)->find($data);
    }

    public function supportsNormalization($value, $format = null)
    {
        if (is_object($value)) {
            // Get the entity manager for this entity
            $entity_manager = $this->doctrine->getManagerForClass(get_class($value));

            if ($entity_manager && $entity_manager->contains($value)) {
                return true;
            }
        }

        return false;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_array($data) && isset($data[0]) && isset($data[1]) && $this->doctrine->getManagerForClass($type);
    }
}
