<?php 

namespace App\Services;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Igdb {

    protected $access_token = null;
    protected $httpClient;
    private $client;

    public function __construct($client ,Twitch $twitch, HttpClientInterface $httpClient) {
       
        $this->client = array_pop($client);
        
        if($this->access_token === null) {

            $this->access_token = $twitch->auth()['access_token'];

        }
        $this->httpClient =  $httpClient;
    }

    public function getGames() { 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/games',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields name, first_release_date, category, status, storyline, summary, version_title, age_ratings, parent_game, aggregated_rating, aggregated_rating_count, follows	;'
                                        ]
                                    )->toArray();
        return $response; 
    }

    public function getGame($id) { 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/games',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                                ." where id = $id ;"
                                        ]
                                    )->toArray();
        return $response;
    }

    public function getGenres() { 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/genres',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields name,url;'
                                        ]
                                    )->toArray();
        return $response;                              
    }
    
    public function getCharacters() { 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/characters',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                        ]
                                    )->toArray();
        return $response;                            
    }

    public function getGameCharacters($id) { 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/characters',
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
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/characters',
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
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/characters',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function getCompanie($id) { 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/characters',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                                ." where games = $id ;"
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function searchGame($search){ 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/games',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                                ." search  \"$search\" ;"
                                        ]
                                    )->toArray(); 
        return $response;
    }


    public function getGameCovers($id){ 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/covers',
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

    public function getModes(){ 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/game_modes',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields *;'
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function getThemes(){ 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/themes',
                                        [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields name;'
                                        ]
                                    )->toArray(); 
        return $response;
    }

    public function getGameVideos($id) { 
        $response =$this->httpClient->request('POST','https://api.igdb.com/v4/game_videos',
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