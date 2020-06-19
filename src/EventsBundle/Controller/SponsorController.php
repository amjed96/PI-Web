<?php

    namespace EventsBundle\Controller;

    use EventsBundle\Entity\Sponsor;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

    /**
     * Sponsor controller.
     *
     * @Route("sponsor")
     */
    class SponsorController extends Controller
    {
        /**
         * Lists all sponsor entities.
         *
         * @Route("/", name="sponsor_index")
         * @Method("GET")
         */
        public function indexAction(Request $request)
        {
            $search =$request->query->get('sponsor');
            $en = $this->getDoctrine()->getManager();
            $sponsor=$en->getRepository("EventsBundle:Sponsor")->findTitle($search);
            return $this->render('sponsor/index.html.twig',array(
                'sponsors' => $sponsor
            ));
        }



        /**
         * Creates a new sponsor entity.
         *
         * @Route("/new", name="sponsor_new")
         * @Method({"GET", "POST"})
         */
        public function newAction(Request $request)
        {
            $sponsor = new Sponsor();
            $form = $this->createForm('EventsBundle\Form\SponsorType', $sponsor);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($sponsor);
                $em->flush();

                return $this->redirectToRoute('sponsor_show', array('idSponsor' => $sponsor->getIdsponsor()));
            }

            return $this->render('sponsor/new.html.twig', array(
                'sponsor' => $sponsor,
                'form' => $form->createView(),
            ));
        }

        /**
         * Finds and displays a sponsor entity.
         *
         * @Route("/{idSponsor}", name="sponsor_show")
         * @Method("GET")
         */
        public function showAction(Sponsor $sponsor)
        {
            $deleteForm = $this->createDeleteForm($sponsor);

            return $this->render('sponsor/show.html.twig', array(
                'sponsor' => $sponsor,
                'delete_form' => $deleteForm->createView(),
            ));
        }

        /**
         * Displays a form to edit an existing sponsor entity.
         *
         * @Route("/{idSponsor}/edit", name="sponsor_edit")
         * @Method({"GET", "POST"})
         */
        public function editAction(Request $request, Sponsor $sponsor)
        {
            $deleteForm = $this->createDeleteForm($sponsor);
            $editForm = $this->createForm('EventsBundle\Form\SponsorType', $sponsor);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('sponsor_index', array('idSponsor' => $sponsor->getIdsponsor()));
            }

            return $this->render('sponsor/edit.html.twig', array(
                'sponsor' => $sponsor,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }

        /**
         * Deletes a sponsor entity.
         *
         * @Route("/{idSponsor}/delete", name="sponsor_delete")
         * @Method("DELETE")
         */
        public function deleteAction( Sponsor $sponsor)
        {
            //$form = $this->createDeleteForm($sponsor);
            //$form->handleRequest($request);

            //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sponsor);
            $em->flush();
            //}

            return $this->redirectToRoute('sponsor_index');
        }

        /**
         * Creates a form to delete a sponsor entity.
         *
         * @param Sponsor $sponsor The sponsor entity
         *
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm(Sponsor $sponsor)
        {
            return $this->createFormBuilder()
                ->setAction($this->generateUrl('sponsor_delete', array('idSponsor' => $sponsor->getIdsponsor())))
                ->setMethod('DELETE')
                ->getForm()
                ;
        }
    }
