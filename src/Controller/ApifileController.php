<?php

namespace App\Controller;


use App\Entity\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
     
class ApifileController extends AbstractController {

    private $fileRepository;

    public function __construct(FileRepository $fileRepository)
{
    $this->fileRepository = $fileRepository;
}


    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("api/upload",name="upload_image")
     * @Method({"POST"})
     */
    public function uploadImage(Request $request)
    {
               $file= new File();

        $uploadedImage=$request->files->get('file');


        /**
         * @var UploadedFile $image
         */
            $image=$uploadedImage;

            $imageName=md5(uniqid()).'.'.$image->guessExtension();

            $image->move($this->getParameter('images_directory'),$imageName);

            $file->setImage($imageName);
            $em=$this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();


        $response=array(

            'code'=>0,
            'message'=>'File Uploaded with success!',
            'errors'=>null,
            'result'=>null

        );


        return new JsonResponse($response,Response::HTTP_CREATED);





    }

     /**
     * @Route("api/images",name="show_images")
     * @Method({"GET"})
     * @return JsonResponse
     */

    public function getImages()
    {
        $images=$this->getDoctrine()->getRepository(File::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($images,'json');
        return new JsonResponse($data);    }

    

    /**
     * @param $id
     * @Route("api/image/{id}",name="show_image")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getImage($id)
    {
        $imageName=$this->getDoctrine()->getRepository(File::class)->find($id)->getImage();


        $response=array(

            'code'=>0,
            'message'=>'get image with success!',
            'errors'=>null,
            'result'=>$imageName

        );

        return new JsonResponse($response,200);




    }


}



  
 


 
