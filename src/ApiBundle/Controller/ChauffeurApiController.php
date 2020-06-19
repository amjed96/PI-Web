<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TransportBundle\Entity\Chauffeur;

/**
 * Class ChauffeurApiController
 * @package ApiBundle\Controller
 * @Route("chauffeurs")
 */
class ChauffeurApiController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function allAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $chaufRepo = $em->getRepository("TransportBundle:Chauffeur");
        $ser = new Serializer([new ObjectNormalizer()]);
        return new JsonResponse($ser->normalize($chaufRepo->findAll()));
    }
}
