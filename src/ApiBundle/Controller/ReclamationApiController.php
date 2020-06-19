<?php

namespace ApiBundle\Controller;

use ReclamationBundle\Entity\reclamation;
use ReclamationBundle\Entity\ReclamationFraude;
use ReclamationBundle\Entity\ReclamationTransport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TransportBundle\Entity\Chauffeur;

/**
 * Class ReclamationApiController
 * @package ApiBundle\Controller
 * @Route("reclamations")
 */
class ReclamationApiController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function allAction(Request $request)
    {
        if (trim($request->get("userCnt")) != null) {
            $em = $this->getDoctrine()->getManager();
            $recRepo = $em->getRepository("ReclamationBundle:reclamation");
            $userRepo = $em->getRepository("AppBundle:User");
            $user = $userRepo->find($request->get("userCnt"));
            $objNorm = new ObjectNormalizer();
            $objNorm->setIgnoredAttributes(["date", "user"]);
            $ser = new Serializer([$objNorm]);
            if ($user->isSuperAdmin())
                return new JsonResponse($ser->normalize($recRepo->findAll()));
            return new JsonResponse($ser->normalize($recRepo->findBy(["user" => $request->get("userCnt")])));
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function indexAction(Request $request, reclamation $id)
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setIgnoredAttributes(['dateCreation']);
        $ser = new Serializer([$normalizer]);
        return new JsonResponse($ser->normalize($id));
    }

    /**
     * @Route("/tools/countResolu", methods={"GET"})
     */
    public function countReclamationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $recRepo = $em->getRepository("ReclamationBundle:reclamation");
        $userRepo = $em->getRepository("AppBundle:User");
        $recs = [];

        if (trim($request->get("userCnt")) != "") {
            $user = $userRepo->find(trim($request->get("userCnt")));
            if ($user)
                $recs = $recRepo->findBy([
                    "etat" => 0, "user" => $user
                ]);
        }

        return new JsonResponse(count($recs));
    }

    /**
     * @Route("/tools/countNonResolu", methods={"GET"})
     */
    public function countReclamationsNonAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $recRepo = $em->getRepository("ReclamationBundle:reclamation");
        $userRepo = $em->getRepository("AppBundle:User");
        $recs = [];

        if (trim($request->get("userCnt")) != "") {
            $user = $userRepo->find(trim($request->get("userCnt")));
            if ($user)
                $recs = $recRepo->findBy([
                    "etat" => 1, "user" => $user
                ]);
        }

        return new JsonResponse(count($recs));
    }






    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAction(Request $request, reclamation $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            $em->remove($id);
            $em->flush();
            return new JsonResponse("{status: 'deleted'}", Response::HTTP_OK);
        } else
            return new JsonResponse(null, 404);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, reclamation $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $id;
        if ($reclamation) {
            $reclamation->setTitre($request->get("sujet"));
            $reclamation->setDescription($request->get("desc"));
            $em->flush();
            return new JsonResponse("{status: 'updated'}", Response::HTTP_OK);
        } else
            return new JsonResponse(null, 404);
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository("AppBundle:User");
        $transportRepo = $em->getRepository("TransportBundle:Transport");
        $chauffeurRepo = $em->getRepository("TransportBundle:Chauffeur");
        //$xData = json_decode($request->getContent(), true);
        $user = $userRepo->find($request->get("user"));
        //$request->request->replace($xData);

        if ($user == null || $user instanceof Chauffeur)
            return new JsonResponse(null, 404);

        $reclamation = null;
        if ($request->get("type") == "fraude") {
            $reclamation = new ReclamationFraude();
            $reclamation->setChauffeur($chauffeurRepo->find($request->get("chauffeur")));
        } else if ($request->get("type") == "transport") {
            $reclamation = new ReclamationTransport();
            $reclamation->setTransport($transportRepo->find($request->get("transport")));
        } else
            $reclamation = new Reclamation();

        $reclamation->setTitre($request->get("sujet"));
        $reclamation->setDescription($request->get("desc"));
        $reclamation->setUser($user);
        $reclamation->setDate(new \DateTime());

        $em->persist($reclamation);
        $em->flush();

        $ser = new Serializer([new ObjectNormalizer()]);
        return new JsonResponse($ser->normalize($reclamation));
    }


}
