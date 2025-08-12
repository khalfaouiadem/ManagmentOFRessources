<?php

namespace App\Repository;

use App\Entity\RessourcesHumaines;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RessourcesHumaines>
 */
class RessourcesHumainesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RessourcesHumaines::class);
    }

    /**
     * @return RessourcesHumaines[] Returns an array of RessourcesHumaines objects
     */
    // This method retrieves all RessourcesHumaines entities ordered by their ID
    public function findAllByAdmin(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id_ref', 'ASC')
            ->getQuery()
            ->getResult();
    }
  public function countByMateriel(): array
    {
        return $this->createQueryBuilder('rh')
            ->select('m.nom_m AS materiel, COUNT(rh.id) AS total')
            ->join('rh.materiels', 'm')
            ->groupBy('m.nom_m')
            ->getQuery()
            ->getResult();
    }
// src/Repository/RessourcesHumainesRepository.php

public function getEvolutionEffectifsMateriels(): array
{
    // Exemple: récupérer le nombre d'effectifs et matériels par code de ressource humaine
    $qb = $this->createQueryBuilder('rh')
        ->select('rh.code AS resource')
        ->addSelect('COUNT(DISTINCT e.id) AS effectifsCount')
        ->addSelect('COUNT(DISTINCT m.id) AS materielsCount')
        ->leftJoin('rh.effects', 'e')
        ->leftJoin('rh.materiels', 'm')
        ->groupBy('rh.id');

    return $qb->getQuery()->getResult();
}

//    public function findOneBySomeField($value): ?RessourcesHumaines
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
