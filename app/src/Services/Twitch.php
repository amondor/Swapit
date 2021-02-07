<?php 

namespace App\Services;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Twitch {

    private $HttpClient;
    private $client;
    private $secret;
    private $grantType;

    public function __construct($client, $secret, $grant_type , HttpClientInterface $HttpClient) {

        
        $this->client = array_pop($client);
        $this->secret = array_pop($secret);
        $this->grantType = array_pop($grant_type);
         

        // $this->grantType = $grant_type;
        $this->HttpClient = $HttpClient;

    }

    public function auth() : array
    {
        $response =  $this->HttpClient->request('POST', 'https://id.twitch.tv/oauth2/token', 
        [
            'query' => ['client_id' => $this->client, 'client_secret' => $this->secret , 'grant_type' => $this->grantType]
        ]);
        if($response->getStatusCode() != 200)
        {
            throw new Exception('auth innaccessible : '.$response->getStatusCode() != 200);
        }

        return($response->toArray());
    }
}
