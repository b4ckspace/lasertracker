<?php

namespace AppBundle\Controller;

use AppBundle\Utils\Strichliste;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HistoryController extends Controller {

    /**
     * @Route("/history", name="history")
     */
    function historyAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $page = $request->query->getInt('page', 1);

        $query = $em->createQuery('SELECT t FROM AppBundle:Transaction t ORDER by t.id DESC');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);


        // TODO: Use DI for strichliste.client
        $client = $this->get('guzzle.client.strichliste');
        $strichliste = new Strichliste\Client($client);

        $strichlisteUserMapping = [];
        foreach($strichliste->getUsers() as $user) {
            $strichlisteUserMapping[$user->getId()] = $user;
        }

        return $this->render('default/history.html.twig', array(
            'pagination' => $pagination,
            'strichlisteUserMapping' => $strichlisteUserMapping
        ));
    }
}