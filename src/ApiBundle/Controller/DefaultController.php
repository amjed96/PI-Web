<?php

namespace ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }

    /**
     * @Route("/login", methods={"POST"})
     */
    function login(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository("AppBundle:User");
        $user = $userRepo->findOneBy([
            "email" => $request->get("email")
        ]);
        if ($user && password_verify($request->get("password"), $user->getPassword())) {
            $ser = new Serializer([new ObjectNormalizer()]);
            return new JsonResponse($ser->normalize($user));
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
