<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transaction;
use AppBundle\Utils\Strichliste;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        $client = $this->get('guzzle.client.strichliste');
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

        $laserOperations = $em->getRepository('AppBundle:LaserOperation');

        $transaction = new Transaction();
        if ($comment) {
            $transaction->setComment($comment);
        }

        $sumDuration = 0;
        foreach($paramFetcher->get('laserOperations') as $id) {
            $laserOperation = $laserOperations->find($id);

            if ($laserOperation === null) {
                throw new BadRequestHttpException('Can\'t find LaserOperation with id ' . $id);
            }

            if ($laserOperation->getTransaction() !== null) {
                throw new BadRequestHttpException('LaserOperation #' . $id . ' is already assigned to an transaction');
            }

            $laserOperation->setTransaction($transaction);
            $sumDuration += $laserOperation->getDuration();
        }

        $transaction->setDuration($sumDuration);
        $laserCredits = $sumDuration / 60;

        $userTransaction = null;
        $operatorTransaction = null;

        if ($paramFetcher->get('chargeAutomatically')) {
            // TODO: Use DI for strichliste.client
            $client = $this->get('guzzle.client.strichliste');
            $strichliste = new Strichliste\Client($client);

            // Check if userId / operatorId exists and is needed
            switch ($mode) {
                case 'operator':
                    $operatorTransaction = $strichliste->createTransaction(new Strichliste\User($operatorId), $laserCredits * -1);
                    $userId = null;
                    break;

                case 'user':
                    $userTransaction = $strichliste->createTransaction(new Strichliste\User($userId), $laserCredits * 2 * -1);
                    $operatorTransaction = $strichliste->createTransaction(new Strichliste\User($operatorId), $laserCredits);
                    break;

                case 'external':
                    $operatorTransaction = $strichliste->createTransaction(new Strichliste\User($operatorId), $laserCredits);
                    $userId = null;
                    break;
            }
        }

        $transaction
            ->setOperatorId($operatorId)
            ->setUserId($userId)
            ->setMode($mode);

        if ($operatorTransaction) {
            $transaction->setOperatorTransactionId($operatorTransaction->getId());
        }

        if ($userTransaction) {
            $transaction->setUserTransactionId($userTransaction->getId());
        }

        $em->persist($transaction);
        $em->flush();

        $this->addFlash('info', 'Gespeichert! Bitte noch an die Materialabrechnung denken!');
        return $this->redirectToRoute('index');
    }

}