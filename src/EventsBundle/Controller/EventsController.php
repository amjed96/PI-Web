<?php

    namespace EventsBundle\Controller;

    use EventsBundle\Entity\Events;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Component\HttpFoundation\Request;
    use Ob\HighchartsBundle\Highcharts\Highchart;



    /**
     * Event controller.
     *
     * @Route("events")
     */
    class EventsController extends Controller
    {
        /**
         * Lists all event entities.
         *
         * @Route("/", name="events_index")
         * @Method("GET")
         */
        public function indexAction(Request $request)
        {

            // Chart
            $series = array(
                array("name" => "Event data stat",    "data" => array(1,2,4,5,6,3,8))
            );
            $categories = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
            $ob = new Highchart();
            $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
            $ob->title->text('Events stat');
            $ob->xAxis->categories($categories);
            $ob->yAxis->categorie($categories);
            $ob->series($series);





            $search =$request->query->get('event');
            $en = $this->getDoctrine()->getManager();
            $event=$en->getRepository("EventsBundle:Events")->findMulti($search);
            return $this->render('events/index.html.twig',array(
                'events' => $event,
                'a' => $ob
            ));
        }

        /**
         * Lists all event entities.
         *
         * @Route("/front", name="eventsfront_index")
         * @Method("GET")
         */
        public function indexfrontAction(Request $request)
        {
            $search =$request->query->get('event');
            $en = $this->getDoctrine()->getManager();
            $event=$en->getRepository("EventsBundle:Events")->findMulti($search);
            return $this->render('events_front/index.html.twig',array(
                'events' => $event
            ));
        }




        /**
         * Creates a new event entity.
         *
         * @Route("/new", name="events_new")
         * @Method({"GET", "POST"})
         */
        public function newAction(Request $request)
        {
            $event = new Events();
            $form = $this->createForm('EventsBundle\Form\EventsType', $event);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                return $this->redirectToRoute('events_show', array('idEvents' => $event->getIdevents()));
            }

            return $this->render('events/new.html.twig', array(
                'event' => $event,
                'form' => $form->createView(),
            ));
        }

        /**
         * Finds and displays a event entity.
         *
         * @Route("/{idEvents}", name="events_show")
         * @Method("GET")
         */
        public function showAction(Events $event)
        {
            $deleteForm = $this->createDeleteForm($event);

            return $this->render('events/show.html.twig', array(
                'event' => $event,
                'delete_form' => $deleteForm->createView(),
            ));
        }

        /**
         * Finds and displays a event entity.
         *
         * @Route("/front/{idEvents}", name="eventsfront_show")
         * @Method("GET")
         */
        public function showfrontAction(Events $event)
        {
            $test = false;
            $exist = $this->getDoctrine()->getManager()->getRepository('EventsBundle:Participation')
                ->findBy(array('events'=>$event,'users'=>$this->getUser()));
            if($exist){
                $test = true;
            }
            //dump($event);exit();
            $deleteForm = $this->createDeleteForm($event);

            return $this->render('events_front/show.html.twig', array(
                'event' => $event,
                'delete_form' => $deleteForm->createView(),
                'test' => $test
            ));
        }

        /**
         * Displays a form to edit an existing event entity.
         *
         * @Route("/{idEvents}/edit", name="events_edit")
         * @Method({"GET", "POST"})
         */
        public function editAction(Request $request, Events $event)
        {
           // $deleteForm = $this->createDeleteForm($event);
            $editForm = $this->createForm('EventsBundle\Form\EventsType', $event);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('events_index', array('idEvents' => $event->getIdevents()));
            }

            return $this->render('events/edit.html.twig', array(
                'event' => $event,
                'edit_form' => $editForm->createView(),
               // 'delete_form' => $deleteForm->createView(),
            ));
        }

        /**
         * Deletes a event entity.
         *
         * @Route("/{idEvents}/delete", name="events_delete")
         * @Method("DELETE")
         */
        public function deleteAction( Events $event)
        {
            //$form = $this->createDeleteForm($event);
            //$form->handleRequest($request);

            //if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($event);
                $em->flush();
            return $this->redirectToRoute('events_index');
        }

        /**
         * Creates a form to delete a event entity.
         *
         * @param Events $event The event entity
         *
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm(Events $event)
        {
            return $this->createFormBuilder()
                ->setAction($this->generateUrl('events_delete', array('idEvents' => $event->getIdevents())))
                ->setMethod('DELETE')
                ->getForm()
                ;
        }






    }
