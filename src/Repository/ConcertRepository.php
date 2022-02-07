<?php

namespace App\Repository;

use App\Entity\Band;
use App\Entity\Concert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Concert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concert[]    findAll()
 * @method Concert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Concert::class);
    }


    /**
     * @method Concert[] findAllNext()
     * */
    public function findAllNext(){
        return $this->createQueryBuilder('c')
            ->andWhere('c.date >= CURRENT_DATE()')
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @method integer findCountAllNext()
     * */
    public function findCountAllNext(){
        return $this->createQueryBuilder('c')
            ->andWhere('c.date >= CURRENT_DATE()')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @method Concert[] findNextWithOffset()
     * */
    public function findNextWithOffset($offset, $limit){
        return $this->createQueryBuilder('c')
            ->andWhere('c.date >= CURRENT_DATE()')
            ->orderBy('c.date', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @method Concert[] findAllNext()
     * */
    public function findAllPrevious(){
        return $this->createQueryBuilder('c')
            ->andWhere('c.date < CURRENT_DATE()')
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @method [] findAllNextOfBand()
     * */
    public function findAllNextOfBand($id){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM concert c
            JOIN concert_band cb ON c.id = cb.concert_id
            WHERE cb.band_id = :id AND c.date > CURRENT_DATE()
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();

        return $result;

/*        $query =$this->createQueryBuilder('c')
            ->join(Band::class,'b')
            ->andWhere('c.date > CURRENT_DATE()')
            ->andWhere('b.id = :id')
            ->setParameter('id', $id)
            ->orderBy('c.date', 'DESC')
            ->getQuery();
            var_dump($query);
        return $query->getResult();*/
    }
    // /**
    //  * @return Concert[] Returns an array of Concert objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Concert
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
