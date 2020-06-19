<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class TransportApiController
 * @package ApiBundle\Controller
 * @Route("transports")
 */
class TransportApiController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function allAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $chaufRepo = $em->getRepository("TransportBundle:Transport");
        $ser = new Serializer([new ObjectNormalizer()]);
        return new JsonResponse($ser->normalize($chaufRepo->findAll()));
    }
}
