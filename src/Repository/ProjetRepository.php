<?php

namespace App\Repository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Projet>
 *
 * @method Projet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projet[]    findAll()
 * @method Projet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Projet::class);
        $this->manager = $manager;
    }


    public function add(Projet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function addComment($contenu,$datecommentaire)
    {
        $newCollaborateur= new Collaborateur();

        $newCollaborateur
            ->setNom($nom)
            ->setPrenom($prenom)
            ->setTache($tache)
            ->setDatedebuttache(\DateTime::createFromFormat('Y-m-d', "2018-09-09"))
            ->setDatefintache(\DateTime::createFromFormat('Y-m-d', "2018-09-09"))
            ->setEtatavancement($etatavancement);

        $this->manager->persist($newCollaborateur);
        $this->manager->flush();
    }


    public function UpdateProjet(Projet $projet):Projet
{
    $this->manager->persist($projet);
    $this->manager->flush();

    return $projet;
}

    public function addProjet($nom, $description, $domainedactivite, $datedebut, $datefin, $budget)
    {
        $newProjet = new Projet();

        $newProjet
            ->setNom($nom)
            ->setDescription($description)
            ->setDomainedactivite($domainedactivite)
            ->setDatedebut(\DateTime::createFromFormat('Y-m-d', "2018-09-09"))
            ->setDatefin(\DateTime::createFromFormat('Y-m-d', "2018-09-09"))
            ->setBudget($budget);

        $this->manager->persist($newProjet);
        $this->manager->flush();
    }

    public function remove(Projet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Projet[] Returns an array of Projet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Projet
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
