<?php

namespace ReclamationBundle\Controller;

use ReclamationBundle\Entity\categorie;
use ReclamationBundle\Entity\reclamation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\HttpFoundation\Response;


/**
 * Reclamation controller.
 *
 * @Route("reclamation")
 */
class reclamationController extends Controller
{
    /**
     * Lists all reclamation entities.
     *
     * @Route("/", name="reclamation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reclamations = $em->getRepository('ReclamationBundle:reclamation')->findAll();

        return $this->render('reclamation/index.html.twig', array(
            'reclamations' => $reclamations,
        ));
    }

    public function indexadminAction(Request $request)
    {

        $paginator = $this->get('knp_paginator');
        $em=$this->getDoctrine()->getManager();
        $oppRep = $em->getRepository(reclamation::class)->findBy(array(
            'etat'=>'non traite',
        ));
        //$alloppQuery = $oppRep->createQueryBuilder('o')->getQuery();
        $opps = $paginator->paginate(
            $oppRep,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('reclamationad/index.html.twig', ['pagination' => $opps]);
        /*$em = $this->getDoctrine()->getManager();

        $reclamations = $em->getRepository('ReclamationBundle:reclamation')->findBy(array(
            'etat'=>'non traite',
        ));

        return $this->render('reclamationad/index.html.twig', array(
            'reclamations' => $reclamations,
        ));*/
    }

    public function indexReclamationAction(){
        $em = $this->getDoctrine()->getManager();

        $reclamations = $em->getRepository('ReclamationBundle:reclamation')->findBy(array(
            'etat' => 'non traite'
        )
        );


        return $this->render('reclamation/showReclamationFront.html.twig', array(
            'reclamations' => $reclamations,
        ));
    }
    public function annulerAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $rec = $em->getRepository('ReclamationBundle:reclamation')->find($request->get('id'));
        $rec->setEtat('annuler');
        $em->flush();
        return $this->redirectToRoute('indexReclamationAction_reclamation');
    }
    /**
     * Creates a new reclamation entity.
     *
     * @Route("/new", name="reclamation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request){
        $reclamation = new Reclamation();
        $form = $this->createForm('ReclamationBundle\Form\reclamationType', $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute('new_reclamation', array('id' => $reclamation->getId()));
        }

        return $this->render('reclamation/new.html.twig', array(
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reclamation entity.
     *
     * @Route("/{id}", name="reclamation_show")
     * @Method("GET")
     */
    public function showAction(reclamation $reclamation)
    {
        $deleteForm = $this->createDeleteForm($reclamation);

        return $this->render('reclamation/show.html.twig', array(
            'reclamation' => $reclamation,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function showadAction(reclamation $reclamation)
    {
        $deleteForm = $this->createDeleteForm($reclamation);

        return $this->render('reclamation/show.html.twig', array(
            'reclamation' => $reclamation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reclamation entity.
     *
     * @Route("/{id}/edit", name="reclamation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, reclamation $reclamation)
    {
        $deleteForm = $this->createDeleteForm($reclamation);
        $editForm = $this->createForm('ReclamationBundle\Form\reclamationType', $reclamation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation_edit', array('id' => $reclamation->getId()));
        }

        return $this->render('reclamation/edit.html.twig', array(
            'reclamation' => $reclamation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function editAdAction(Request $request, reclamation $reclamation)
    {
        $deleteForm = $this->createDeleteForm($reclamation);
        $editForm = $this->createForm('ReclamationBundle\Form\reclamationType', $reclamation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('reclamation_edit', array('id' => $reclamation->getId()));
        }

        return $this->render('reclamationad/edit.html.twig', array(
            'reclamation' => $reclamation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function statistiqueAction(){
        $stat = $this->getDoctrine()->getManager()->getRepository('ReclamationBundle:reclamation')->countAll();
        return $this->render('reclamationad/statistique.html.twig', array(
            'a' => 5000,
            'stat' => $stat
        ));
    }

    /**
     * Deletes a reclamation entity.
     *
     * @Route("/{id}/delete", name="reclamation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, reclamation $reclamation)
    {
        //$form = $this->createDeleteForm($reclamation);
        //$form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reclamation);
            $em->flush();
        //}

        return $this->redirectToRoute('affiche');
    }
    public function deleteadAction(Request $request, reclamation $reclamation)
    {
        //$form = $this->createDeleteForm($reclamation);
        //$form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reclamation);
            $em->flush();
        //}

        return $this->redirectToRoute('affiche');
    }

    /**
     * Creates a form to delete a reclamation entity.
     *
     * @param reclamation $reclamation The reclamation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(reclamation $reclamation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reclamation_delete', array('id' => $reclamation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function rechercheAction(Request $request) {
        $paginator = $this->get('knp_paginator');
        $em=$this->getDoctrine()->getManager();
        $oppRep = $em->getRepository(reclamation::class);
        $alloppQuery = $oppRep->createQueryBuilder('o')->getQuery();
        $opps = $paginator->paginate(
            $alloppQuery,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('reclamation/recherche.html.twig', ['pagination' => $opps]);
        //$reclamation=$em->getRepository(reclamation::class)->findAll();
        /*if ($request->isMethod( "POST")) {
            $titre = $request->get('titre');
            $reclamation = $em->getRepository('ReclamationBundle:reclamation')->findBy(array(
                'titre' => $titre
            ));
        }
        return $this->render('reclamation/recherche.html.twig',array(

            'reclamations' => $reclamation,
        ));*/


    }

    public function traiterAction(reclamation $reclamation)
    {
        $id = $reclamation->getId();
        $this->getDoctrine()->getManager()->getRepository(reclamation::class)->traiter($id);
        return $this->redirectToRoute('affiche');
    }

    /**
     * Lists all reclamation entities.
     *
     * @Route("/pdf", name="pdf")
     * @Method("GET")
     */
    public function pdfAction(Request $request)
    {
        $rec=$this->getDoctrine()->getManager()->getRepository(reclamation::class)->find($request->get('id'));
        $snappy = $this->get("knp_snappy.pdf");
        $snappy->setOption("encoding","UTF-8");
        $html = "
        <h1> Nom Reclamation : ". $rec->getTitre() ." </h1> <p> <strong> Description : </strong>". $rec->getDescription() ."</p> <p> <strong> Etat : </strong>". $rec->getEtat() ."</p>";
        $filename = "downloadpdf";

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.pdf"'
            )
        );
    }

    public function chartAction()
    {
        $pieChart = new PieChart();
        $em= $this->getDoctrine();
        $classes = $em->getRepository(categorie::class)->findAll();
        $totalEtudiant=0;
        foreach($classes as $classe) {
            $totalEtudiant=$totalEtudiant+$classe->getNbEtudiants();
        }

        $data= array();
        $stat=['classe', 'nbEtudiant'];
        $nb=0;
        array_push($data,$stat);
        foreach($classes as $classe) {
            $stat=array();
            array_push($stat,$classe->getNom(),(($classe->getNbEtudiants()) *100)/$totalEtudiant);
            $nb=($classe->getNbEtudiants() *100)/$totalEtudiant;
            $stat=[$classe->getNom(),$nb];
            array_push($data,$stat);

        }

        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('Pourcentages des étudiants par niveau');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render('@Graphe\Default\index.html.twig', array('piechart' => $pieChart));

    }
}
