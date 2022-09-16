<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


    
class ApiuserController extends AbstractController
{
      /**
     *
     * @Route("/user/Add", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    {
        $email = $data['email'];
        $password = $data['password'];
      
        if (empty($email) || empty($password) ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $this->userRepository->addUser($email, $password);
        return new JsonResponse(['status' => 'User created!'], Response::HTTP_CREATED);
    }
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($User);
            $em->flush();
            $view = $this->view($User, Response::HTTP_OK);
            return $this->handleView($view);
        } else {
            throw new FormException($form);
        }
    }

     /**
     * @Route("/regisster", name="rregister", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $password = $request->get('password');
        $email = $request->get('email');
        $username = $request->get('username');
        $user = new User();
        $user->setPassword($encoder->encodePassword($user,$password));
        $user->setEmail($email);
        $user->setUusername($username);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->json([
            'user' => $user->getEmail()
        ]);
    }

      


    
}