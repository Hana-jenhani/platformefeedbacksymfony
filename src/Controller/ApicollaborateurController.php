<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CollaborateurRepository;
use App\Repository\TaskListRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Collaborateur;
use App\Entity\TaskList;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Task;
use App\Form\CollaborateurType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\RequestParam;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
     /**
     * @Route("/apicollaborateur", name="appi_collaborateurr")
     */
class ApicollaborateurController extends AbstractController
{
    private $collaborateurRepository;
    private $taskListRepository;
    private $entityManager;
   


public function __construct(CollaborateurRepository $collaborateurRepository,  EntityManagerInterface $entityManager,  TaskListRepository $taskListRepository)
{
    $this->collaborateurRepository = $collaborateurRepository;
    $this->taskListRepository = $taskListRepository;
    $this->entityManager = $entityManager;


}



    /**
     * @Route("/collaborateur/liste", name="liste", methods={"GET"})
     */
    public function liste(CollaborateurRepository $collaborateurRepository )
    {
        //on récupére liste client
        $collaborateurs = $collaborateurRepository->findAll();
        //encodeur json
        $encoders = [new JsonEncoder()]; 
        //convertir en tableau
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        // Serialize your object in Json
        $jsonObject = $serializer->serialize($collaborateurs, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
               
            }
        ]); 
        $response = new Response($jsonObject);
        $response->headers->set('Content-Type', 'application/json');
       return $response;

    }

       /**
     * @Route("/collaborateur/lire/{id}", name="lire")
     */
    public function show($id) {
        $collaborateur = $this->getDoctrine()->getRepository(Collaborateur::class)->find($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($collaborateur);
        return new JsonResponse($formatted);    }

          /**
     *
     * @Route("/collaborateur/Add", name="addcollaborateur", methods={"POST"})
     */
    public function addCollaborateur(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    {
        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $tache = $data['tache'];
        $datedebuttache = $data['datedebuttache'];
        $datefintache = $data['datefintache'];
        $etatavancement = $data['etatavancement'];
        
        if (empty($nom) || empty($prenom) || empty($tache) || empty($datedebuttache) || empty($datefintache) || empty($etatavancement) ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->collaborateurRepository->addCollaborateur($nom, $prenom, $tache, $datedebuttache, $datefintache, $etatavancement );
        return new JsonResponse(['status' => 'Collaborateur created!'], Response::HTTP_CREATED);
    }
        if ($form->isSubmitted() && $form->isValid()){
           
            $em = $this->getDoctrine()->getManager();
        
            $em->persist($collaborateur);
            $em->flush();
            $view = $this->view($collaborateur, Response::HTTP_OK);
            return $this->handleView($view);
        } else {
            throw new FormException($form);
        }
    }


    

       /**
 * @Route("/collaborateur/{id}", name="update_collaborateur", methods={"PUT"})
 */
public function update($id, Request $request): JsonResponse
{
    $collaborateur = $this->collaborateurRepository->findOneBy(['id' => $id]);
    $data = json_decode($request->getContent(), true);

    empty($data['nom']) ? true : $collaborateur->setNom($data['nom']);
    empty($data['prenom']) ? true : $collaborateur->setPrenom($data['prenom']);
    empty($data['tache']) ? true : $collaborateur->setTache($data['tache']);
    empty($data['datedebuttache']) ? true : $collaborateur->setDatedebuttache(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
    empty($data['datefintache']) ? true : $collaborateur->setDatefintache(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
    empty($data['etatavancement']) ? true : $collaborateur->setEtatavancement($data['etatavancement']);
    ;

    $updatedCollaborateur = $this->collaborateurRepository->UpdateCollaborateur($collaborateur);

    return new JsonResponse(['status' => 'Collaborateur updated!'], Response::HTTP_OK);
}


          /**
     * @Rest\FileParam(name="image", description="The background of the list"  ,nullable=false , image=true)
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     *  @param taskList $list
     *  @Route("/upload/{id}", name="uploaad", methods={"POST"})
     */
    public function backgroundListsAction(Request $request, ParamFetcher $paramFetcher , int $id )
    {
    $list = $this->taskListRepository->findOneBy(['id' => $id]);
     $currentBackgound = $list->getBackground();
     if (!is_null($currentBackgound)){
        $filesystem = new Filesystem();
        $filesystem->remove(
            $this->getUploadsDir() . $currentBackgound
      );
     }
     /** @var UploadedFile $file */
      $file = $paramFetcher->get('image');
      if ($file) {
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move(
            $this->getUploadsDir(),
            $filename
        );
        $list->setBackground($filename);
        $list->setBackgroundPath('/uploads/' . $filename);
        $this->entityManager->persist($list);
        $this->entityManager->flush();
        $data = $request->getUriForPath(
            $list->getBackgroundPath()
        );
        return $this->json($data, 201);
      }
      throw new HttpException(400, 'Invalid ');
    }


    
    private function getUploadsDir(){
        return $this->getParameter('images_directory');
    }

    



      /**
     * @Route("/api/images",name="show_images")
     * @Method({"GET"})
     * @return JsonResponse
     */

    public function getImages()
    {

        $collaborateur = $this->getDoctrine()->getRepository(Collaborateur::class)->findall();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($collaborateur);
        return new JsonResponse($formatted);  
    }

     /**
     * @param $id
     * @Route("/api/image/{id}",name="show_image")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getBackground($id)
    {
        $list = $this->collaborateurRepository->find($id)->getBackground();


        $response=array(

            'code'=>0,
            'message'=>'get image with success!',
            'errors'=>null,
            'result'=>$list 

        );

        return new JsonResponse($response,200);




    }

     /**
     * @Route("/colla/images",name="show_images")
     * @Method({"GET"})
     * @return JsonResponse
     */


    public function like1(Collaborateur $post, PostLikeRepository $liikeRepository):Response
    {
        $manager = $this->getDoctrine()->getManager();

        // $user = $this->getUser();
        /* if (!$user) return $this->json([
             'code' => 403,
             'message' => "Unauthorized"
         ], 403);*/
        $user=$manager->getRepository(User::class)->find(1);
        if ($post->isLikedByUser($user)){
            $like = $manager->getRepository(PostLike::class)->findOneBy([
                'collaborateur' => $post,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();
            /* return $this->json([
                 'code' => 200,
                 'message' => 'Like bien supprimé',
                 'likes' => $liikeRepository->count(['randonnee' => $post] )
             ], 200);*/
            $response=array();
            array_push($response,['code'=>200,'respose'=>'success']);
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($response);
            return new JsonResponse($formatted);
        }

        $like = new PostLike();
        $like->setCollaborateur($post)
            ->setUser($user);
        $manager->persist($like);
        $manager->flush();

        $response=array();
        array_push($response,['code'=>200,'respose'=>'success']);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($response);
        return new JsonResponse($formatted);
    }

    

     /**
     * @Route("/collaborateur/Supp/{id}", name="collaborateur_api")
     *
     */
    function Delete($id, CollaborateurRepository $repository){
        $collaborateur =  $this->getDoctrine()->getRepository(Collaborateur::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $id=$collaborateur->getId();
        $em->remove($collaborateur);
        $em->flush();
        $response=array();
        array_push($response,['code'=>200,'respose'=>'success','id'=>$id]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($response);
        return new JsonResponse($formatted);

    }
}
