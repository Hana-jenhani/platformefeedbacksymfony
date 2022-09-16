<?php

namespace App\Repository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Reclamation::class);
        $this->manager = $manager;
    }

    public function AffecterReservation()
    {


        $conn = $this->getEntityManager()->getConnection();
        $id=$_GET['idAffected'];
        $sqlu = "UPDATE Reservation SET etat='Affected' WHERE `id`= $id ";


        $stmt = $conn->prepare($sqlu);

        $stmt->execute();

    }
    public function RefuserReservation()
    {


        $conn = $this->getEntityManager()->getConnection();
        $id=$_GET['idRefuser'];
        $sqlu = "UPDATE Reservation SET etat='Refuser' WHERE `id`= $id ";


        $stmt = $conn->prepare($sqlu);

        $stmt->execute();

    }

    public function addReclamation($designation, $categorie, $datereclamation, $etat)
    {
        $newReclamation = new Reclamation();

        $newReclamation
            ->setDesignation($designation)
            ->setCategorie($categorie)
            ->setDatereclamation(\DateTime::createFromFormat('Y-m-d', "2018-09-09"))
            ->setEtat($etat);

        $this->manager->persist($newReclamation);
        $this->manager->flush();
    }

    public function UpdateReclamation(Reclamation $reclamation):Reclamation
    {
        $this->manager->persist($reclamation);
        $this->manager->flush();
    
        return $reclamation;
    }

    public function add(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
