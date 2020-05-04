<?php

namespace App\Controller;


use App\Entity\Pelicula;
use App\Repository\PeliculaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class PeliculaController
 * @package App\Controller
 * 
 * @Route(path="/api/")
 * 
 */

class PeliculasController extends AbstractController
{

    /**
     * @Route("pelicula",name="add_pelicula", methods={"POST"})
     */
    public function add(Request $request): Jsonresponse
    {
        $data = json_decode($request->getContent(),true);

        $nombre = $data['nombre'];
        $genero = $data['genero'];
        $descripcion = $data['descripcion'];

        if(empty($nombre) || empty($genero)){
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        
        $this->getDoctrine()
        ->getRepository(Pelicula::class)->savePelicula($nombre, $genero, $descripcion);

        return new JsonResponse(['status' => "Movie created!"], Response::HTTP_CREATED);
    }

    /**
     * @Route("pelicula/{id}", name="get_one_pelicula", methods={"GET"})
     */
    public function getPelicula(int $id) : JsonResponse
    {
        $pelicula = $this->getDoctrine()
                            ->getRepository(Pelicula::class)
                                ->findOneBy(['id' => $id]);

        $data = [
            'id' => $pelicula->getId(),
            'nombre' => $pelicula->getNombre(),
            'genero' => $pelicula->getGenero(),
            'descripcion' => $pelicula->getDescripcion(),
        ];


        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("peliculas", name="get_all_peliculas", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $peliculas = $this->getDoctrine()
                            ->getRepository(Pelicula::class)
                                ->findAll();
        $data = [];

        foreach ($peliculas as $pelicula) {
            $data[] = [
                'id' => $pelicula->getId(),
                'nombre' => $pelicula->getNombre(),
                'genero' => $pelicula->getGenero(),
                'descripcion' => $pelicula->getDescripcion(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("pelicula/{id}", name="update_pelicula", methods={"PUT"})
     */
    public function updatePelicula($id, Request $request): JsonResponse
    {
        $pelicula = $this->getDoctrine()
                            ->getRepository(Pelicula::class)
                                ->findOneBy(['id' => $id]);

        $data = json_decode($request->getContent(), true);

        empty($data['nombre']) ? true : $pelicula->setNombre($data['nombre']);
        empty($data['genero']) ? true : $pelicula->setGenero($data['genero']);
        empty($data['descripcion']) ? true : $pelicula->setDescripcion($data['descripcion']);

        $updatedPelicula = $this->getDoctrine()
                                ->getRepository(Pelicula::class)
                                    ->updatePelicula($pelicula);

		return new JsonResponse(['status' => 'Movie updated!'], Response::HTTP_OK);
    }

    /**
     * @Route("pelicula/{id}", name="delete_pelicula", methods={"DELETE"})
     */
    public function deletePelicula($id): JsonResponse
    {
        $pelicula = $this->getDoctrine()
                            ->getRepository(Pelicula::class)
                                ->findOneBy(['id' => $id]);

        $this->getDoctrine()
                ->getRepository(Pelicula::class)
                    ->removePelicula($pelicula);

        return new JsonResponse(['status' => 'Movie deleted'], Response::HTTP_OK);
    }

}
