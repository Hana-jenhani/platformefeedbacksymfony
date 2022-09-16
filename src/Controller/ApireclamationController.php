<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

     /**
     * @Route("/apireclamation", name="appi_")
     */
class ApireclamationController extends AbstractController {

private $reclamationRepository;

public function __construct(ReclamationRepository $reclamationRepository)
{
    $this->reclamationRepository = $reclamationRepository;
}

    /**
     * @Route("/reclamation/liste", name="liste", methods={"GET"})
     */
    public function liste(reclamationRepository $reclamationsRepo)
    {
        //on récupére liste client
        $reclamations = $reclamationsRepo->findAll();
        //encodeur json
        $encoders = [new JsonEncoder()]; 
        //convertir en tableau
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        // Serialize your object in Json
        $jsonObject = $serializer->serialize($reclamations, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId(); 
            }
        ]); 
        $response = new Response($jsonObject);
        $response->headers->set('Content-Type', 'application/json');
       return $response;
    }

     /**
     * @Route("/reclamation/lire/{id}", name="lire")
     */
    public function show($id) {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse($formatted);    }

    /**
     *
     * @Route("/reclamation/Add", name="addreclamation", methods={"POST"})
     */
    public function addReclamation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    {
        $designation = $data['designation'];
        $categorie = $data['categorie'];
        $datereclamation = $data['datereclamation'];
        $etat = $data['etat'];
       
        if (empty($designation) || empty($categorie) || empty($datereclamation) || empty($etat)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->reclamationRepository->addReclamation($designation, $categorie, $datereclamation, $etat );
        return new JsonResponse(['status' => 'Reclamation created!'], Response::HTTP_CREATED);
    }
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();
            $view = $this->view($reclamation, Response::HTTP_OK);
            return $this->handleView($view);
        } else {
            throw new FormException($form);
        }
    }
    /**
     * @Route("/reclamation/Supp/{id}", name="reclamation_api")
     *
     */
    function Delete($id, ReclamationRepository $repository){
        $reclamation =  $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $id=$reclamation->getId();
        $em->remove($reclamation);
        $em->flush();
        $response=array();
        array_push($response,['code'=>200,'respose'=>'success','id'=>$id]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($response);
        return new JsonResponse($formatted);

    }   

        /**
 * @Route("/reclamation/{id}", name="update_reclamation", methods={"PUT"})
 */
public function update($id, Request $request): JsonResponse
{
    $reclamation = $this->reclamationRepository->findOneBy(['id' => $id]);
    $data = json_decode($request->getContent(), true);

   
    empty($data['designation']) ? true : $reclamation->setDesignation($data['designation']);
    empty($data['categorie']) ? true : $reclamation->setCategorie($data['categorie']);
    empty($data['datereclamation']) ? true : $reclamation->setDatereclamation(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
    empty($data['etat']) ? true : $reclamation->setEtat($data['etat']);
    ;

    $updatedReclamation = $this->reclamationRepository->UpdateReclamation($reclamation);

    return new JsonResponse(['status' => 'Reclamation updated!'], Response::HTTP_OK);
}

}