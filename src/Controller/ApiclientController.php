<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

     /**
     * @Route("/apiclient", name="appi_client")
     */
class ApiclientController extends AbstractController {

private $clientRepository;

public function __construct(ClientRepository $clientRepository, SerializerInterface $serialize)
{
    $this->clientRepository = $clientRepository;
}

  

     /**
     * @Route("/client/lire/{id}", name="lire")
     */
    public function show($id) {
        $client = $this->getDoctrine()->getRepository(Client::class)->find($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($client);
        return new JsonResponse($formatted);    }

          /**
     * @Route("/client/liste", name="liste", methods={"GET"})
     */
    public function liste(ClientRepository $clientRepository )
    {
        //on récupére liste client
        $clients = $clientRepository->findAll();
        //encodeur json
        $encoders = [new JsonEncoder()]; 
        //convertir en tableau
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        // Serialize your object in Json
        $jsonObject = $serializer->serialize($clients, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
               
            }
        ]); 
        $response = new Response($jsonObject);
        $response->headers->set('Content-Type', 'application/json');
       return $response;

    }

    /**
     *
     * @Route("/client/Add", name="adddclient", methods={"POST"})
     */
    public function addClient(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    {
        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $cin = $data['cin'];
        $tel = $data['tel'];
        $adresse = $data['adresse'];
        $datenaissance = $data['datenaissance'];
        if (empty($nom) || empty($prenom) || empty($cin) || empty($tel) || empty($adresse) || empty($datenaissance)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->clientRepository->addClient($nom, $prenom, $cin, $tel, $adresse, $datenaissance );
        return new JsonResponse(['status' => 'Client created!'], Response::HTTP_CREATED);
    }
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $view = $this->view($client, Response::HTTP_OK);
            return $this->handleView($view);
        } else {
            throw new FormException($form);
        }
    }

           /**
 * @Route("/client/{id}", name="update_client", methods={"PUT"})
 */
public function update($id, Request $request): JsonResponse
{
    $client = $this->clientRepository->findOneBy(['id' => $id]);
    $data = json_decode($request->getContent(), true);

    empty($data['nom']) ? true : $client->setNom($data['nom']);
    empty($data['prenom']) ? true : $client->setPrenom($data['prenom']);
    empty($data['cin']) ? true : $client->setCin($data['tache']);
    empty($data['tel']) ? true : $client->setTel($data['tel']);
    empty($data['adresse']) ? true : $client->setAdresse($data['adresse']);
    empty($data['datenaissance']) ? true : $client->setDatefintache(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));

    $updatedClient = $this->clientRepository->UpdateClient($client);

    return new JsonResponse(['status' => 'Client updated!'], Response::HTTP_OK);
}





       /**
     * @Route("/client/Supp/{id}", name="client_api", methods={"DELETE"})
     *
     */
    function Delete($id, ClientRepository $repository){
        $client =  $this->getDoctrine()->getRepository(Client::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $id=$client->getId();
        $em->remove($client);
        $em->flush();
        $response=array();
        array_push($response,['code'=>200,'respose'=>'success','id'=>$id]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($response);
        return new JsonResponse($formatted);

    }

}