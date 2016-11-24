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
    function statisticsAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $page = $request->query->getInt('page', 1);

        $query = $em->createQuery('SELECT t FROM AppBundle:Transaction t');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 5);


        // TODO: Use DI for strichliste.client
        $client = $this->get('guzzle.client.laserminuten');
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