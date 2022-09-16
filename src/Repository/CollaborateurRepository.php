<?php

namespace App\Repository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Collaborateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collaborateur>
 *
 * @method Collaborateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collaborateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collaborateur[]    findAll()
 * @method Collaborateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollaborateurRepository extends ServiceEntityRepository

{
    private $manager;
    

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
      
    )
    {
        parent::__construct($registry, Collaborateur::class);
        $this->manager = $manager;
      
       
    }
    public function add(Collaborateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function UpdateCollaborateur(Collaborateur $collaborateur):Collaborateur
    {
        $this->manager->persist($collaborateur);
        $this->manager->flush();
    
        return $collaborateur;
    }

    public function addCollaborateur($nom, $prenom, $tache, $datedebuttache, $datefintache, $etatavancement)
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

    public function remove(Collaborateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Collaborateur[] Returns an array of Collaborateur objects
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

//    public function findOneBySomeField($value): ?Collaborateur
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
