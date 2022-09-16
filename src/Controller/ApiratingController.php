<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RatingRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Rating;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

     /**
     * @Route("/rating", name="appi_")
     */

    class ApiratingController extends AbstractController {

        private $ratingRepository;
        
        public function __construct(RatingRepository $ratingRepository)
        {
            $this->ratingRepository = $ratingRepository;
        }
        
            /**
             * @Route("/rating/liste", name="liste_rating", methods={"GET"})
             */
            public function liste(RatingRepository $ratingRepo)
            {
                //on récupére liste client
                $ratings = $ratingRepo->findAll();
                //encodeur json
                $encoders = [new JsonEncoder()]; 
                //convertir en tableau
                $normalizers = [new ObjectNormalizer()];
        
                $serializer = new Serializer($normalizers, $encoders);
                // Serialize your object in Json
                $jsonObject = $serializer->serialize($ratings, 'json', [
                    'circular_reference_handler' => function ($object) {
                        return $object->getId(); 
                    }
                ]); 
                $response = new Response($jsonObject);
                $response->headers->set('Content-Type', 'application/json');
               return $response;
            }
        
             /**
             * @Route("/rat/lire/{id}", name="lire_rat")
             */
            public function show($id) {
                $rating = $this->getDoctrine()->getRepository(Rating::class)->find($id);
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($rating);
                return new JsonResponse($formatted);    }


       
        
            /**
             *
             * @Route("/rating/Add", name="add_rating", methods={"POST"})
             */
            public function addRating(Request $request): JsonResponse
            {
                $data = json_decode($request->getContent(), true);
            {
                $iduser = $data['iduser'];
                $idcollaborateur = $data['idcollaborateur'];
                $value = $data['value'];
                if (empty($iduser ) || empty($idcollaborateur) || empty($value) ) {
                    throw new NotFoundHttpException('Expecting mandatory parameters!');
                }
        
                $this->ratingRepository->addRating($iduser, $idcollaborateur, $value);
                return new JsonResponse(['status' => 'Rating created!'], Response::HTTP_CREATED);
            }
                if ($form->isSubmitted() && $form->isValid()){
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($rating);
                    $em->flush();
                    $view = $this->view($rating, Response::HTTP_OK);
                    return $this->handleView($view);
                } else {
                    throw new FormException($form);
                }
            }




        }        