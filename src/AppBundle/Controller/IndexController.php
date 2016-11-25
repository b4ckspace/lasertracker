<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transaction;
use AppBundle\Utils\Strichliste;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller {

    /**
     * @Route("/", name="index")
     */
    function indexAction() {

        $laserOperations = $this->getDoctrine()
            ->getRepository('AppBundle:LaserOperation')
            ->findBy(
                ['transaction' => null],
                ['id' => 'DESC']
            );

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

        // TODO: Sanity checking (if operatorId/userId exists / is set etc...)

        $mode = $paramFetcher->get('mode');
        $comment = $paramFetcher->get('comment');
        $userId = $paramFetcher->get('userId');
        $operatorId = $paramFetcher->get('operatorId');

        $sumSeconds = 0;
        $laserOperations = $em->getRepository('AppBundle:LaserOperation');

        $transaction = new Transaction();
        if ($comment) {
            $transaction->setComment($comment);
        }

        foreach($paramFetcher->get('laserOperations') as $id) {
            $laserOperation = $laserOperations->find($id);
            $laserOperation->setTransaction($transaction);

            $sumSeconds += $laserOperation->getDuration();
        }

        $transaction->setDuration($sumSeconds);
        $laserMinutes = $sumSeconds / 60;

        $userTransactionId = null;
        $operatorTransactionId = null;

        if ($paramFetcher->get('chargeAutomatically')) {
            // TODO: Use DI for strichliste.client
            $client = $this->get('guzzle.client.laserminuten');
            $strichliste = new Strichliste\Client($client);

            switch ($mode) {
                case 'operator':
                    $operatorTransactionId = $strichliste->createTransaction(new Strichliste\User($operatorId), $laserMinutes * -1);
                    $userId = null;
                    break;

                case 'user':
                    $userTransactionId = $strichliste->createTransaction(new Strichliste\User($userId), $laserMinutes * 2 * -1);
                    $operatorTransactionId = $strichliste->createTransaction(new Strichliste\User($operatorId), $laserMinutes);
                    break;

                case 'external':
                    $operatorTransactionId = $strichliste->createTransaction(new Strichliste\User($operatorId), $laserMinutes);
                    $userId = null;
                    break;
            }
        }

        $transaction
            ->setOperatorTransactionId($operatorTransactionId)
            ->setUserTransactionId($userTransactionId)
            ->setOperatorId($operatorId)
            ->setUserId($userId)
            ->setMode($mode);

        $em->persist($transaction);
        $em->flush();

        $this->addFlash('info', 'Gespeichert! Bitte noch an die Materialabrechnung denken!');
        return $this->redirectToRoute('index');
    }
}