<?php
declare(strict_types=1);

namespace App\Repository\MovementCategory\Doctrine;

use App\Entity\MovementCategory;
use App\Exception\MovementCategory\MovementCategoryNotFoundException;
use App\Repository\DoctrineBaseRepository;

class DoctrineMovementCategoryRepository extends DoctrineBaseRepository
{
    protected static function entityClass(): string
    {
        return MovementCategory::class;
    }

    public function save(MovementCategory $movementCategory): void
    {
        $this->saveEntity($movementCategory);
    }

    public function remove(MovementCategory $movementCategory): void
    {
        $this->removeEntity($movementCategory);
    }

    public function findOneByIdOrFail(string $id): ?MovementCategory
    {
        if (null === $movementCategory = $this->objectRepository->findOneBy(['id' => $id])) {
            throw MovementCategoryNotFoundException::fromId($id);
        }

        return $movementCategory;
    }

}