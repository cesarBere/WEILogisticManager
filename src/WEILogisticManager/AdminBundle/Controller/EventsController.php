<?php

namespace WEILogisticManager\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use WEILogisticManager\AdminBundle\Entity\Event;
use WEILogisticManager\AdminBundle\Form\Type\EventType;
use Doctrine\ORM\EntityManager;
use JMS\SecurityExtraBundle\Annotation\Secure;

class EventsController extends Controller
{
    /**
     * @Route("/events", name="_admin_events")
     * @Template("WEILogisticManagerAdminBundle:Events:index.html.twig")
     * @Secure("ROLE_USER")
     */
    public function indexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->from('WEILogisticManagerAdminBundle:Event', 'e');
        $qb->select('e');
        $events = $qb->getQuery()->getResult();

        return array(
            'events' => $events,
        );
    }

    /**
     * @Route("/events/create", name="_admin_events_create")
     * @Template("WEILogisticManagerAdminBundle:Events:form.html.twig")
     * @Secure("ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new EventType(), new Event());

        $form->handleRequest($request);

        if ($form->isValid()) {
            //Persist object in database
            $data = $form->getData();

            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            return $this->redirect($this->generateUrl('_admin_events'));
        }

        return array(
            'form' => $form->createView(),
            'action' => "Create",
        );
    }

    /**
     * @Route("/events/update/{id}", name="_admin_events_udpate")
     * @Template("WEILogisticManagerAdminBundle:Events:form.html.twig")
     * @Secure("ROLE_USER")
     */
    public function updateAction(Event $event, Request $request)
    {
        $form = $this->createForm(new EventType(), $event);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            return $this->redirect($this->generateUrl('_admin_events'));
        }


        return array(
            'form' => $form->createView(),
            'action' => "Update",
        );
    }

    /**
     * @Route("/events/delete/{id}", name="_admin_events_delete")
     * @Template()
     * @Secure("ROLE_USER")
     */
    public function deleteAction(Event $event)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirect($this->generateUrl('_admin_events'));
    }

    /**
     * @Route("/events/select/{id}", name="_admin_events_select")
     * @Template()
     * @Secure("ROLE_USER")
     */
    public function selectAction(Event $event)
    {
        $session = $this->getRequest()->getSession();

        $session->set('event', $event);

        return $this->redirect($this->generateUrl('_admin_events'));
    }
}
