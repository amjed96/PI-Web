<?php

namespace TransportBundle\Controller;

use TransportBundle\Entity\Chauffeur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Chauffeur controller.
 *
 */
class ChauffeurController extends Controller
{
    /**
     * Lists all chauffeur entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $chauffeurs = $em->getRepository('TransportBundle:Chauffeur')->findAll();

        return $this->render('chauffeur/index.html.twig', array(
            'chauffeurs' => $chauffeurs,
        ));
    }

    /**
     * Creates a new chauffeur entity.
     *
     */
    public function newAction(Request $request)
    {
        $chauffeur = new Chauffeur();
        $form = $this->createForm('TransportBundle\Form\ChauffeurType', $chauffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chauffeur);
            $em->flush();

            return $this->redirectToRoute('chauffeur_show', array('matricule' => $chauffeur->getMatricule()));
        }

        return $this->render('chauffeur/new.html.twig', array(
            'chauffeur' => $chauffeur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a chauffeur entity.
     *
     */
    public function showAction(Chauffeur $chauffeur)
    {
        $deleteForm = $this->createDeleteForm($chauffeur);

        return $this->render('chauffeur/show.html.twig', array(
            'chauffeur' => $chauffeur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing chauffeur entity.
     *
     */
    public function editAction(Request $request, Chauffeur $chauffeur)
    {
        $deleteForm = $this->createDeleteForm($chauffeur);
        $editForm = $this->createForm('TransportBundle\Form\ChauffeurType', $chauffeur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chauffeur_edit', array('matricule' => $chauffeur->getMatricule()));
        }

        return $this->render('chauffeur/edit.html.twig', array(
            'chauffeur' => $chauffeur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a chauffeur entity.
     *
     */
    public function deleteAction(Request $request, Chauffeur $chauffeur)
    {
        $form = $this->createDeleteForm($chauffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chauffeur);
            $em->flush();
        }

        return $this->redirectToRoute('chauffeur_index');
    }

    /**
     * Creates a form to delete a chauffeur entity.
     *
     * @param Chauffeur $chauffeur The chauffeur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Chauffeur $chauffeur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chauffeur_delete', array('matricule' => $chauffeur->getMatricule())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
