<?php

namespace EventsBundle\Controller;

use EventsBundle\Entity\Events;
use EventsBundle\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Participation controller.
 *
 * @Route("participation")
 */
class ParticipationController extends Controller
{
    /**
     * Lists all participation entities.
     *
     * @Route("/", name="participation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $participations = $em->getRepository('EventsBundle:Participation')->findAll();

        return $this->render('participation/index.html.twig', array(
            'participations' => $participations,
        ));
    }

    /**
     * Creates a new participation entity.
     *
     * @Route("/{id}/new", name="participation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Events $events)
    {
        $participant = $this->getUser();
        $participation = new Participation();
        //$form = $this->createForm('EventsBundle\Form\ParticipationType', $participation);
        //$form->handleRequest($request);
        $participation->setUsers($participant);
        if($events->getIdEvents() != null)
        {
            $events->setNbPlaces($events->getNbPlaces()-1);
            $this->getDoctrine()->getManager()->flush();

        }
        //dump($events);exit();
        $participation->setEvents($events);
        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();


        //}

        return $this->redirectToRoute('participation_show', array('id' => $participation->getId()));
    }

    /**
     * Finds and displays a participation entity.
     *
     * @Route("/{id}", name="participation_show")
     * @Method("GET")
     */
    public function showAction(Participation $participation)
    {
        $deleteForm = $this->createDeleteForm($participation);

        $event = $participation->getEvents();
        $user = $participation->getUsers();

        return $this->render('participation/show.html.twig', array(
            'participation' => $participation,
            'delete_form' => $deleteForm->createView(),
            'user' => $user,
            'event' => $event
        ));
    }

    /**
     * Finds and displays a participation entity.
     *
     * @Route("/annuler/{idevent}/{iduser}", name="participation_annuler")
     * @Method("GET")
     */
    public function annulerAction($idevent,$iduser)
    {
        $em = $this->getDoctrine()->getManager();
        //dump($idevent);dump($iduser);exit();
        $particpation=$em->getRepository('EventsBundle:Participation')->findOneBy(array('events'=>$idevent,'users'=>$iduser));
        if ($particpation != null)
        {
            $event = new Events();
            $event = $particpation->getEvents();
            $event->setNbPlaces($event->getNbPlaces()+1);
            $this->getDoctrine()->getManager()->flush();
           // dump();exit();
        }
        $em->remove($particpation);
        $em->flush();
        return $this->redirectToRoute('eventsfront_index');
    }
    /**
     * Displays a form to edit an existing participation entity.
     *
     * @Route("/{id}/edit", name="participation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Participation $participation)
    {
        $deleteForm = $this->createDeleteForm($participation);
        $editForm = $this->createForm('EventsBundle\Form\ParticipationType', $participation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('participation_edit', array('id' => $participation->getId()));
        }

        return $this->render('participation/edit.html.twig', array(
            'participation' => $participation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a participation entity.
     *
     * @Route("/{id}", name="participation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Participation $participation)
    {
        $form = $this->createDeleteForm($participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participation);
            $em->flush();
        }

        return $this->redirectToRoute('participation_index');
    }

    /**
     * Creates a form to delete a participation entity.
     *
     * @param Participation $participation The participation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Participation $participation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('participation_delete', array('id' => $participation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
