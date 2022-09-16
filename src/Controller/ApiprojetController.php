<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProjetRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Projet;
use App\Form\ProjetType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
     * @Route("/apiprojet", name="appi_projet")
     */
class ApiprojetController extends AbstractController{
private $projetRepository;

public function __construct(ProjetRepository $projetRepository)
{
    $this->projetRepository = $projetRepository;
}

   /**
     * @Route("/projet/liste", name="liste", methods={"GET"})
     */
    public function liste(ProjetRepository $projetsRepo)
    {
        //on récupére liste client
        $projets = $projetsRepo->findAll();
        //encodeur json
        $encoders = [new JsonEncoder()]; 
        //convertir en tableau
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        // Serialize your object in Json
        $jsonObject = $serializer->serialize($projets, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
               
            }
        ]); 
        $response = new Response($jsonObject);
        $response->headers->set('Content-Type', 'application/json');
       return $response;

    }
     /**
     * @Route("/projet/lire/{id}", name="lire")
     */
    public function show($id) {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->find($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($projet);
        return new JsonResponse($formatted);  
      }
    /**
     *
     * @Route("/projet/Add", name="addprojet", methods={"POST"})
     */
    public function addProjet(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    {
        $nom = $data['nom'];
        $description = $data['description'];
        $domainedactivite = $data['domainedactivite'];
        $datedebut = $data['datedebut'];
        $datefin = $data['datefin'];
        $budget = $data['budget'];
        if (empty($nom) || empty($description) || empty($domainedactivite) || empty($datedebut) || empty($datefin) || empty($budget)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->projetRepository->addProjet($nom, $description, $domainedactivite, $datedebut, $datefin, $budget );
        return new JsonResponse(['status' => 'Projet created!'], Response::HTTP_CREATED);
    }
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($projet);
            $em->flush();
            $view = $this->view($projet, Response::HTTP_OK);
            return $this->handleView($view);
        } else {
            throw new FormException($form);
        }
    }

    /**
 * @Route("/projet/{id}", name="update_projet", methods={"PUT"})
 */
public function update($id, Request $request): JsonResponse
{
    $projet = $this->projetRepository->findOneBy(['id' => $id]);
    $data = json_decode($request->getContent(), true);

    empty($data['nom']) ? true : $projet->setNom($data['nom']);
    empty($data['description']) ? true : $projet->setDescription($data['description']);
    empty($data['domainedactivite']) ? true : $projet->setDomainedactivite($data['domainedactivite']);
    empty($data['datedebut']) ? true : $projet->setDatedebut(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
    empty($data['datefin']) ? true : $projet->setDatefin(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
    empty($data['budget']) ? true : $projet->setBudget($data['budget']);
    ;

    $updatedProjet = $this->projetRepository->UpdateProjet($projet);

    return new JsonResponse(['status' => 'Projet updated!'], Response::HTTP_OK);
}

      /**
     * @Route("/projet/Supp/{id}", name="projet_api")
     *
     */
    function Delete($id, ProjetRepository $repository){
        $projet =  $this->getDoctrine()->getRepository(Projet::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $id=$projet->getId();
        $em->remove($projet);
        $em->flush();
        $response=array();
        array_push($response,['code'=>200,'respose'=>'success','id'=>$id]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($response);
        return new JsonResponse($formatted);

    }
}
