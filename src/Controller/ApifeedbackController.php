<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FeedbackRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Feedback;
use App\Form\FeedbackType;

     /**
     * @Route("/apifeedback", name="appifeedback")
     */
class ApifeedbackController extends AbstractController
{
    private $feedbackRepository;

public function __construct(FeedbackRepository $feedbackRepository)
{
    $this->feedbackRepository = $feedbackRepository;
}

    /**
     * @Route("/feedback/liste", name="liste", methods={"GET"})
     */
    public function liste(FeedbackRepository $feedbackRepository )
    {
        //on récupére liste client
        $feedbacks = $feedbackRepository->findAll();
        //encodeur json
        $encoders = [new JsonEncoder()]; 
        //convertir en tableau
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        // Serialize your object in Json
        $jsonObject = $serializer->serialize($feedbacks, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
               
            }
        ]); 
        $response = new Response($jsonObject);
        $response->headers->set('Content-Type', 'application/json');
       return $response;

    }

     /**
     * @Route("/feedback/lire/{id}", name="lire")
     */
    public function show($id) {
        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($feedback);
        return new JsonResponse($formatted); 
       }

     /**
     *
     * @Route("/feedback/Add", name="addfeedback", methods={"POST"})
     */
    public function addFeedback(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    {
        $contenu = $data['contenu'];
        $datefeedback = $data['datefeedback'];
        
        if (empty($contenu) || empty($datefeedback) ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->feedbackRepository->addFeedback($contenu, $datefeedback);
        return new JsonResponse(['status' => 'Feedback created!'], Response::HTTP_CREATED);
    }
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();
            $view = $this->view($feedback, Response::HTTP_OK);
            return $this->handleView($view);
        } else {
            throw new FormException($form);
        }
    }


    /**
     * @Route("/feedback/Supp/{id}", name="feedback_api")
     *
     */
    function Delete($id, FeedbackRepository $repository){
        $feedback =  $this->getDoctrine()->getRepository(Feedback::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $id=$feedback->getId();
        $em->remove($feedback);
        $em->flush();
        $response=array();
        array_push($response,['code'=>200,'respose'=>'success','id'=>$id]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($response);
        return new JsonResponse($formatted);

    }

    /**
 * @Route("/feedback/{id}", name="update_feedback", methods={"PUT"})
 */
public function update($id, Request $request): JsonResponse
{
    $feedback = $this->feedbackRepository->findOneBy(['id' => $id]);
    $data = json_decode($request->getContent(), true);

    empty($data['contenu']) ? true : $feedback->setContenu($data['contenu']);
    empty($data['datefeedback']) ? true : $feedback->setDatefeedback(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
 
    ;

    $updatedFeedback = $this->feedbackRepository->UpdateFeedback($feedback);

    return new JsonResponse(['status' => 'feedback updated!'], Response::HTTP_OK);
}
}