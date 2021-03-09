<?php

namespace App\Service;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\ContextAwareQueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\ContextAwareQueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\ContextAwareQueryResultItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

class ApiPlatformExtensionApplier
{
    private iterable $collectionExtensions;

    private iterable $itemExtensions;

    public function __construct(iterable $collectionExtensions, iterable $itemExtensions)
    {
        $this->collectionExtensions = $collectionExtensions;
        $this->itemExtensions = $itemExtensions;
    }

    /**
     * @param QueryBuilder $builder
     * @param array        $identifiers
     * @param string       $resourceClass
     * @param string|null  $operationName
     * @param array        $context
     *
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function applyToItem(
        QueryBuilder $builder,
        array $identifiers,
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ) {
        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->itemExtensions as $extension) {
            $extension->applyToItem(
                $builder,
                $queryNameGenerator,
                $resourceClass,
                $identifiers,
                $operationName,
                $context
            );
            if ($extension instanceof ContextAwareQueryResultItemExtensionInterface
                && $extension->supportsResult($resourceClass, $operationName, $context)
            ) {
                return $extension->getResult($builder, $resourceClass, $operationName, $context);
            }
        }

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $resourceClass
     * @param string|null  $operationName
     * @param array        $context
     *
     * @return iterable
     */
    public function applyToCollection(
        QueryBuilder $builder,
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ) {
        $queryNameGenerator = new QueryNameGenerator();
        /** @var ContextAwareQueryCollectionExtensionInterface $extension */
        foreach ($this->collectionExtensions as $extension) {
            $extension->applyToCollection($builder, $queryNameGenerator, $resourceClass, $operationName, $context);

            if ($extension instanceof ContextAwareQueryResultCollectionExtensionInterface
                && $extension->supportsResult($resourceClass, $operationName, $context)
            ) {
                return $extension->getResult($builder, $resourceClass, $operationName, $context);
            }
        }

        return $builder->getQuery()->execute();
    }
}
