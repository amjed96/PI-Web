<?php

namespace ReclamationBundle\Controller;

use ReclamationBundle\Entity\categories;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Categorie controller.
 *
 * @Route("categories")
 */
class categoriesController extends Controller
{
    /**
     * Lists all categories entities.
     *
     * @Route("/", name="categorie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('ReclamationBundle:categories')->findAll();

        return $this->render('categories/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new categories entity.
     *
     * @Route("/new", name="categorie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $categorie = new categories();
        $form = $this->createForm('ReclamationBundle\Form\categorieType', $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('categorie_show', array('id' => $categorie->getId()));
        }

        return $this->render('categories/new.html.twig', array(
            'categories' => $categorie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a categories entity.
     *
     * @Route("/{id}/delete", name="categorie_show")
     * @Method("GET")
     */
    public function showAction(categories $categorie)
    {
        $deleteForm = $this->createDeleteForm($categorie);

        return $this->render('categories/show.html.twig', array(
            'categories' => $categorie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing categories entity.
     *
     * @Route("/{id}/edit", name="categorie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, categories $categorie)
    {
        $deleteForm = $this->createDeleteForm($categorie);
        $editForm = $this->createForm('ReclamationBundle\Form\categorieType', $categorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_edit', array('id' => $categorie->getId()));
        }

        return $this->render('categories/edit.html.twig', array(
            'categories' => $categorie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a categories entity.
     *
     * @Route("/{id}", name="categorie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, categories $categorie)
    {
        $form = $this->createDeleteForm($categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }

    /**
     * Creates a form to delete a categories entity.
     *
     * @param categories $categorie The categories entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(categories $categorie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categorie_delete', array('id' => $categorie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
