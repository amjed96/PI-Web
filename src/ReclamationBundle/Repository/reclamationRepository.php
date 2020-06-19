<?php

namespace ReclamationBundle\Repository;

use ReclamationBundle\Entity\reclamation;

/**
 * reclamationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class reclamationRepository extends \Doctrine\ORM\EntityRepository
{
    public function count($id)
    {
        $Query = $this->getEntityManager()->createQuery(
            "select count(*) from utilisateur where categorieId = :id"
        )->setParameter('id',$id);
    }

    public function traiter($id)
    {
        $Query = $this->getEntityManager()->createQuery(
            "UPDATE ReclamationBundle:reclamation r SET r.etat = 'traitée' WHERE r.id = :id"
        )->setParameter('id',$id);
        return $Query->getResult();
    }
    public function countAll()
    {
        $Query = $this->getEntityManager()->createQuery(
            "select count(* ) from ReclamationBundle:reclamation r where r.etat = 'traite'"
        );
            return $Query->getResult();
        /*return $this->createQueryBuilder('a')
            ->select('COUNT(a)')->from(reclamation::class)
            ->where('a.etat' = 'traite')
            ->getQuery()
            ->getSingleScalarResult();*/
    }

    public function countComments($id)
    {
        try {
            return $this->createQueryBuilder('a')
                ->select('COUNT(a)')
                ->where('a.reclamation.etat = traite')s
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }
}