<?php

namespace AppBundle\Utils\Strichliste;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Client {

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @param \GuzzleHttp\Client $client
     */
    function __construct(\GuzzleHttp\Client $client) {
        $this->client = $client;
    }

    /**
     * @return array|null
     */
    function getUsers() {
        $response = $this->client->get('/api/user');

        if ($response->getStatusCode() !== 200) {
            // TODO: Throw exception
            return null;
        }

        $users = json_decode($response->getBody(), true);
        if (!$users) {
            // TODO: Throw exception
            return null;
        }

        $result = [];
        foreach ($users['entries'] as $user) {
            $result[] = User::byUserResponse($user);
        }

        return $result;
    }

    /**
     * @param User $user
     * @param $amount
     * @return bool
     */
    function createTransaction(User $user, $amount) {

        $uri = sprintf('/api/user/%d/transaction', $user->getId());
        $response = $this->client->post($uri, [
            'json' => [
                'value' => sprintf("%.2f", $amount)
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            // TODO: Better exception.
            throw new BadRequestHttpException('Strichliste API returned with status code ' . $response->getStatusCode());
        }

        return true;
    }

}