<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LaserOperations;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

//      * Method({"POST"})

class ApiController extends Controller  {

    /**
     * @Route("/api/laseroperations")
     */
    function createAction(Request $request) {
        $duration = $request->query->getInt('duration');

        if (!$duration) {
            throw new BadRequestHttpException('Invalid value for duration');
        }

        $operation = new LaserOperations();
        $operation->setDuration($duration);

        $em = $this->getDoctrine()->getManager();
        $em->persist($operation);

        $em->flush();

        return $this->json($operation);
    }
}