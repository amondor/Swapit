<?php 

namespace App\Services;


use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\Entity\Game;

use Symfony\Component\Serializer\Serializer;

class Igdb {

    protected $access_token = null;
    protected $httpClient;
    private $client;
    private $interfaceManager;
    
    private $encoders;
    private $normalizers;
    private $serializer;


    // private $normalizer = new ObjectNormalizer($classMetadataFactory);
    // private $serializer = new Serializer([$normalizer]);
   
    public function __construct($client ,Twitch $twitch, HttpClientInterface $httpClient, EntityManagerInterface $em) {
       
        $this->client = array_pop($client);
        
        if($this->access_token === null) {

            $this->access_token = $twitch->auth()['access_token'];

        }

        $this->httpClient =  $httpClient;
        $this->interfaceManager =  $em;
        $this->encoders = array(new JsonEncoder());
        $this->normalizers = array(new ObjectNormalizer());
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    public function initCron() {
      
        $this->initCronGames();
    }
    
    public function initCronGames() {

        //  for ($i=0; $i < ($this->countGames()/500); $i++) {  
        //     $offset = ($items == 0)? 0 : $items*500;
        //     $response = $this->getGamesList($offset,500);
        //     $this->serializeDatas($response, Game::class);
        // // }

        for ($i=0; $i < ($this->countGames()/500); $i++) {  
            $offset = ($i == 0)? 0 : $i*500;
            $response = $this->getGamesList($offset,500);
            $this->serializeDatas($response, Game::class);
        }
    }

    public function serializeDatas($datas, $class) {

        foreach($datas as $data) {
            $this->serializeData($data, $class);
        }

    }

    public function serializeData($data, $class) {

            if(!array_key_exists("parent_game", $data))
            {
                $productSerialized = $this->serializer->serialize($data, 'json',['groups' => 'seo']);
            
                $productDeserialized = $this->serializer->deserialize($productSerialized, $class, 'json', ['groups' => 'seo']);
    
                $this->interfaceManager->persist($productDeserialized);
                $this->interfaceManager->flush();
            }  

    }

    public function getGamesList($offset, $limit) {

        $response = $this->httpClient->request(
                        'POST','https://api.igdb.com/v4/games',
                        [
                        'headers' => 
                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                        'body' => 'fields name, first_release_date, status, storyline, summary, version_title, age_ratings, parent_game, aggregated_rating, aggregated_rating_count, follows;
                                    limit '."$limit;".'
                                    offset '."$offset;",
                        ]
                    )->toArray();
        return $response;

    }

    public function countGames() {

        $response = $this->httpClient->request(
                        'POST','https://api.igdb.com/v4/games/count',
                        [
                            'headers' => [
                                    'Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token
                                ],
                        ]
                    )->toArray();
        
        return $response['count'];

    }


    public function getGame($id) {

        $response = $this->httpClient->request(
                        'POST','https://api.igdb.com/v4/games',
                        [
                            'headers' => [
                                    'Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token
                                ],
                            'body' => 'fields name, first_release_date, status, storyline, summary, version_title, age_ratings, parent_game, aggregated_rating, aggregated_rating_count, follows;'
                                    ." where id = $id ;"
                        ]
                    )->toArray();
        return array_pop($response);
        
    }

    public function getGenres() { 
        $response = $this->httpClient->request(
                        'POST','https://api.igdb.com/v4/genres',
                        [
                            'headers' => [
                                    'Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token
                                ],
                            'body' => 'fields name,url;'
                        ]
                    )->toArray();
        return $response;                              
    }
    
    public function getCharacters() { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/characters',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                        ]
                                    )->toArray();
        return $response;                            
    }

    public function getGameCharacters($id) { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/characters',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                                ." where games = $id ;"
                                        ]
                                    )->toArray();
        return $response;
    }

    public function getCharacter_mug_shots($id) { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/characters',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields image_id,url;'
                                                ." where games = $id ;"
                                        ]
                                    )->toArray();
        return $response;
    }

    public function getCompanies() { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/characters',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function getCompanie($id) { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/characters',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                                ." where games = $id ;"
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function searchGame($search) { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/games',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                                ." search  \"$search\" ;"
                                        ]
                                    )->toArray(); 
        return $response;
    }


    public function getGameCovers($id) { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/covers',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                                ." where game = $id ;
                                                limit 1"
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function getModes() { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/game_modes',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function getThemes() { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/themes',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields name;'
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function getGameVideos($id) { 
        $response = $this->httpClient->request('POST','https://api.igdb.com/v4/game_videos',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                                ." where games = $id ;
                                                limit 3;"
                                        ]
                                    )->toArray(); 
        return $response;
    } 
}