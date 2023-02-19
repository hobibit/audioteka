<?php

namespace App\Repository;

use App\Entity\CartProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartProducts>
 *
 * @method CartProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartProducts[]    findAll()
 * @method CartProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProducts::class);
    }

    public function save(CartProducts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CartProducts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CartProduct[] Returns an array of CartProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findOneBySomeField(string $cartID, string $productID): ?CartProducts
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.cart_id = :cartID')
            ->andWhere('c.product_id = :productID')
            ->setParameter('cartID', $cartID)
            ->setParameter('productID', $productID)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
