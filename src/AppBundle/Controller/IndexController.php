<?php

namespace AppBundle\Controller;

use AppBundle\Utils\Strichliste;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller {

    /**
     * @Route("/")
     */
    function indexAction() {

        $laserOperations = $this->getDoctrine()
            ->getRepository('AppBundle:LaserOperations')
            ->findBy([
                'userId' => null,
                'operatorId' => null
            ], [
                'id' => 'DESC'
            ]);

        $client = $this->get('guzzle.client.laserminuten');
        $strichliste = new Strichliste\Client($client);

        return $this->render('default/index.html.twig', array(
            'laserOperations' => $laserOperations,
            'users' => $strichliste->getUsers()
        ));
    }
}