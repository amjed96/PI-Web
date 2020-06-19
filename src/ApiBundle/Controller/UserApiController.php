<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class UserApiController
 * @package ApiBundle\Controller
 * @Route("/users")
 */
class UserApiController extends Controller
{

    /**
     * @Route("/", methods={"GET"})
     */
    public function allAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository("AppBundle:User");
        $ser = new Serializer([new ObjectNormalizer()]);
        return new JsonResponse($ser->normalize($userRepo->findAll()));
    }

    /**
     * @Route("/", methods={"POST"})
     */
    function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setEmail($request->get("email"));
        $user->setPlainPassword($request->get("password"));
        $user->setUsername($request->get("username"));
        if ($request->get("roles") == "Admin")
            $user->addRole(User::ROLE_SUPER_ADMIN);
        if ($request->get("roles") == "Volentaire")
            $user->addRole(User::ROLE_VOL);
        else if ($request->get("roles") == "NGO")
            $user->addRole(User::ROLE_NGO);
        else if ($request->get("roles") == "Réfugé")
            $user->addRole(User::ROLE_REF);

        $user->addRole(User::ROLE_DEFAULT);

        $em->persist($user);
        $em->flush();

        return new JsonResponse($user, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{user}", methods={"PUT"})
     */
    function update(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        if ($user != null) {
            $user->setEmail($request->get("email"));

            if (trim($request->get("password")) != null)
                $user->setPlainPassword($request->get("password"));

            $user->setUsername($request->get("username"));
            if ($request->get("roles") == "Admin")
                $user->addRole(User::ROLE_SUPER_ADMIN);

            $user->addRole(User::ROLE_DEFAULT);
            $em->flush();

            return new JsonResponse($user, Response::HTTP_ACCEPTED);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/{user}", methods={"DELETE"})
     */
    function delete(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        if ($user != null) {
            $em->remove($user);
            $em->flush();

            return new JsonResponse($user, Response::HTTP_ACCEPTED);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

}
