<?php

namespace App\Controller;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;


class AuthController extends ApiController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

     /**
     * @Route("/register", name="register", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $request = $this->transformJsonBody($request);
        
        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');
            

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'username' => new Assert\Length(array('min' => 1)),
            'password' => new Assert\Length(array('min' => 1)),
            'email' => new Assert\Email()
        ));
        $violations = $validator->validate( $constraint);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }
        $user = new User($username);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setUsername($username);

        $password = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
        return new JsonResponse(["success" => $user->getUsername(). " has been registered!"], 200);
    }


  

   

   

   

    
     /**
     * @Route("/delete", name="delete_u", methods={"DELETE"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */


    public function delete(Request $request)
    {
        try {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $email      = $request->request->get('email');
            $userData   = $repository->findOneBy([
                'email' => $email,
            ]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userData);
            $entityManager->flush();
            return new Response(sprintf('%s successfully removed.', $email));
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "User not found!"], 500);
        }
    }

     /**
     * @Route("/logout", name="security_logout")
     * @throws Exception
     */
    public function logout(): void
    {
        throw new Exception('This should never be reached!');
    }
    

    

}