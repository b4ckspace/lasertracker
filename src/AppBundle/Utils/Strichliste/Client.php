<?php

namespace AppBundle\Utils\Strichliste;


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
            $result[] = new User($user['id'], $user['name'], $user['balance']);
        }

        return $result;
    }

}