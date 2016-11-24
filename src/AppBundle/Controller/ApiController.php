<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LaserOperation;
use AppBundle\Entity\LaserOperations;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiController extends Controller  {

    /**
     * @Route("/api/laseroperations")
     */
    function createAction(Request $request) {
        $duration = $request->query->getInt('duration');

        if (!$duration) {
            throw new BadRequestHttpException('Invalid value for duration');
        }

        $operation = new LaserOperation();
        $operation->setDuration($duration);

        $em = $this->getDoctrine()->getManager();
        $em->persist($operation);

        $em->flush();

        return $this->json($operation);
    }
}