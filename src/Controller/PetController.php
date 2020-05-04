<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Repository\PetRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * Class PetController
 * @package App\Controller
 * 
 * @Route(path="/api_test/")
 * 
 */

class PetController extends AbstractController
{
    
    // private $petRepository;

    // public function _construct(PetRepository $petRepository)
    // {
    //     $this->petRepository = $petRepository;
    // }

    /**
     * @Route("pet",name="add_pet", methods={"POST"})
     */
    public function add_test(Request $request): Jsonresponse
    {
        $data = json_decode($request->getContent(),true);

        $name = $data['name'];
        $type = $data['type'];
        $photoUrls = $data['photoUrls'];

        if(empty($name) || empty($type)){
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        
        $this->getDoctrine()
        ->getRepository(Pet::class)->savePet($name, $type, $photoUrls);

        return new JsonResponse(['status' => "Pet created!"], Response::HTTP_CREATED);
    }

}
