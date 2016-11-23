<?php

namespace AppBundle\Controller;

use AppBundle\Utils\Strichliste;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
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

        // TODO: Use DI for strichliste.client
        $client = $this->get('guzzle.client.laserminuten');
        $strichliste = new Strichliste\Client($client);

        return $this->render('default/index.html.twig', array(
            'laserOperations' => $laserOperations,
            'users' => $strichliste->getUsers()
        ));
    }

    /**
     * @QueryParam(name="mode", requirements="(user|operator|external)", strict=true)
     * @QueryParam(name="operatorId", requirements="\d+", strict=true)
     * @QueryParam(name="userId", requirements="\d+")
     * @QueryParam(name="comment", default=null)
     * @QueryParam(name="chargeAutomatically", requirements="\d", default="0")
     * @QueryParam(name="laserOperations", map=true, requirements="\d+", strict=true)
     *
     * @Route("/charge")
     */

    function chargeAction(ParamFetcher $paramFetcher) {
        $em = $this->getDoctrine()->getManager();

        $mode = $paramFetcher->get('mode');
        $comment = $paramFetcher->get('comment');
        $userId = $paramFetcher->get('userId');
        $operatorId = $paramFetcher->get('operatorId');

        $sumSeconds = 0;
        $laserOperations = $em->getRepository('AppBundle:LaserOperations');

        foreach($paramFetcher->get('laserOperations') as $id) {
            $laserOperation = $laserOperations->find($id);

            $laserOperation
                ->setOperatorId($operatorId)
                ->setUserId($userId);

            if ($comment) {
                $laserOperation->setComment($comment);
            }

            $sumSeconds += $laserOperation->getDuration();
        }

        $laserMinutes = $sumSeconds / 60;

        if ($paramFetcher->get('chargeAutomatically')) {
            // TODO: Use DI for strichliste.client
            $client = $this->get('guzzle.client.laserminuten');
            $strichliste = new Strichliste\Client($client);

            switch ($mode) {
                case 'operator':
                    $strichliste->createTransaction(new Strichliste\User($operatorId), $laserMinutes * -1);
                    break;

                case 'user':
                    $strichliste->createTransaction(new Strichliste\User($userId), $laserMinutes * 2 * -1);
                    $strichliste->createTransaction(new Strichliste\User($operatorId), $laserMinutes);
                    break;

                case 'external':
                    $strichliste->createTransaction(new Strichliste\User($operatorId), $laserMinutes);
                    break;
            }
        }

        $em->flush();
    }
}