<?php

namespace App\Repository;

use App\Entity\Twitter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Twitter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Twitter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Twitter[]    findAll()
 * @method Twitter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TwitterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Twitter::class);
    }

    /**
     * @param int $page
     *
     * @return array
     */
    public function getHiddenTweetsIdByUser($user_id) 
    {
        // Get previously user hidden tweets
        $twittersToHide = $this->findByUserId($user_id);

        $result = [];

        foreach($twittersToHide as $tweetToHide) {
            array_push($result, $tweetToHide->getTwitterId());       
        }
        
        return $result;
    }

    // /**
    //  * @return Twitter[] Returns an array of Twitter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Twitter
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
