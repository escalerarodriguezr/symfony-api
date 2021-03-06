<?php
declare(strict_types=1);

namespace App\Repository\User\Doctrine;

use App\Entity\User;
use App\Exception\User\UserAlreadyExistsException;
use App\Exception\User\UserNotFoundException;
use App\Repository\DoctrineBaseRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class DoctrineUserRepository extends DoctrineBaseRepository
{
    protected static function entityClass(): string
    {
        return User::class;
    }

    public function save(User $user): void
    {
        try{
            $this->saveEntity($user);
        }catch (UniqueConstraintViolationException){
            throw UserAlreadyExistsException::fromEmail($user->getEmail());
        }
    }

    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }

    public function findOneByIdOrFail(string $id): ?User
    {
        if (null === $user = $this->objectRepository->findOneBy(['id' => $id])) {
            throw UserNotFoundException::fromId($id);
        }

        return $user;

    }

        public function findOneByEmailOrFail(string $email): ?User
    {
        if (null === $user = $this->objectRepository->findOneBy(['email' => $email])) {
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;

    }

    public function findOneByActivationCodeOrFail(string $code): ?User
    {

        if (null === $user = $this->objectRepository->findOneBy(['activationCode' => $code])) {
            throw UserNotFoundException::fromActivationCode($code);
        }

        return $user;

    }

    public function findOneByIdWithQueryBuilder(string $id): ?User
    {
        $qb = $this->objectRepository->createQueryBuilder('u');
        $query = $qb
            ->where(
                $qb->expr()->eq('u.id', ':id')
            )
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findOneByIdWithDQL(string $id): ?User
    {
        $query = $this->getEntityManager()->createQuery('SELECT u FROM App\Entity\User u WHERE u.id = :id');
        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnyByIdWithNativeQuery(string $id): ?User
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(User::class, 'u');

        $query = $this->getEntityManager()->createNativeQuery('SELECT * FROM user WHERE id = :id', $rsm);
        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findOneByIdWithPlainSQL(string $id): array
    {
        $params = [
            ':id' => $this->getEntityManager()->getConnection()->quote($id),
        ];
        $query = 'SELECT * FROM user WHERE id = :id';

        return $this->getEntityManager()->getConnection()->executeQuery(\strtr($query, $params))->fetchAllAssociative();
    }


}