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
     * @return User[]
     */
    function getUsers() {
        $response = $this->client->get('/api/user');

        if ($response->getStatusCode() !== 200) {
            throw new BadRequestHttpException('Strichliste API returned with status code ' . $response->getStatusCode());
        }

        $users = json_decode($response->getBody(), true);
        if (!$users) {
            throw new \Exception('Can\'t parse json response');
        }

        $result = [];
        foreach ($users['entries'] as $user) {
            $result[] = User::byResponse($user);
        }

        return $result;
    }

    /**
     * @param User $user
     * @param $amount
     * @return Transaction
     */
    function createTransaction(User $user, $amount) {

        $uri = sprintf('/api/user/%d/transaction', $user->getId());
        $response = $this->client->post($uri, [
            'json' => [
                'value' => sprintf("%.2f", $amount)
            ]
        ]);

        if ($response->getStatusCode() !== 201) {
            throw new BadRequestHttpException('Strichliste API returned with status code ' . $response->getStatusCode());
        }

        $json = json_decode($response->getBody(), true);
        if (!$json) {
            throw new \Exception('Can\'t parse json response');
        }

        return Transaction::byResponse($json);
    }

}