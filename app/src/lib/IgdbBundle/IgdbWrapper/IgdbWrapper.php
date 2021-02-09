<?php
namespace App\IgdbBundle\IgdbWrapper;


use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\IgdbBundle\IgdbWrapper\TwitchWrapper;
use App\IgdbBundle\Mapper\GameModeMapper;
use App\IgdbBundle\Mapper\GameMapper;
use App\IgdbBundle\Mapper\GenreMapper;
use App\IgdbBundle\Mapper\CompanyMapper;
use App\IgdbBundle\Mapper\PlatformMapper;

use Symfony\Component\Serializer\Serializer;

class IgdbWrapper 
{

protected $access_token = null;
private $client;
protected $httpClient;
private $url;
private $interfaceManager;

private $encoders;
private $normalizers;
private $serializer;

public function __construct($client , $url, TwitchWrapper $twitch, HttpClientInterface $httpClient, EntityManagerInterface $em) {
   
    $this->client = array_pop($client);
    $this->url = array_pop($url);

    if($this->access_token === null) {
        $this->access_token = $twitch->auth()['access_token'];
    }

    $this->httpClient =  $httpClient;
    $this->interfaceManager =  $em;
    $this->encoders = array(new JsonEncoder());
    $this->normalizers = array(new ObjectNormalizer());
    $this->serializer = new Serializer($this->normalizers, $this->encoders);
}

public function initCron()
{
    $this->initCronGames();
}

public function initCronGames() 
{
    // $response = $this->getGenres();
    // $this->serializeDatas($response,GenreMapper::class);

    // $response = $this->getGamesModes();
    // $this->serializeDatas($response,GameModeMapper::class);
  
    // $response = $this->getPlatforms();
    // $this->serializeDatas($response,PlatformMapper::class);

    // $companiesCount = $this->countCompanies()/500;
    // for ($i=0; $i < $companiesCount; $i++) {  
    //     $offset = ($i == 0)? 0 : $i*500;
    //     $response = $this->getCompanies($offset);
    //     $this->serializeDatas($response, CompanyMapper::class);
    // }

    $gameCount = $this->countGames()/500;
    for ($i=0; $i < $gameCount; $i++) {  
        $offset = ($i == 0)? 0 : $i*500;
        $response = $this->getGames($offset);
        $this->serializeDatas($response, GameMapper::class);
    }
}

public function serializeDatas($datas, $class) 
{
    foreach($datas as $data) {
        $this->serializeData($data, $class);
    }
}

public function serializeData($data, $class) 
{
        if(!array_key_exists("parent_game", $data))
        {      
            $productMapped = $class::map($data, $this->interfaceManager);

            if ($productMapped !== null) {
                $this->interfaceManager->persist($productMapped);
                $this->interfaceManager->flush();
            }
           
        }
}

public function getGames($offset, $limit = 500) {

    $response = $this->httpClient->request(
                    'POST','https://api.igdb.com/v4/games',
                    [
                    'headers' => 
                        ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                    'body' => 'fields name, first_release_date, status, storyline, summary, version_title, age_ratings, parent_game, aggregated_rating, aggregated_rating_count, follows, genres, involved_companies, game_modes, platforms;
                                where status = null;
                                where total_rating != null;
                                limit '."$limit;".'
                                offset '."$offset;",
                    ]
                )->toArray();
               
    return $response;

}

public function getGenres($limit = 30) { 
    $response = $this->httpClient->request(
                    'POST','https://api.igdb.com/v4/genres',
                    [
                        'headers' => [
                                'Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token
                            ],
                        'body' => 'fields name, slug;
                        limit '."$limit;"
                    ]
                )->toArray();

    return $response;                              
}

public function getCompanies($offset, $limit = 500) { 
    $response = $this->httpClient->request('POST','https://api.igdb.com/v4/companies',
                                    [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields name, country , description, developed;
                                        limit '."$limit;".'
                                        offset '."$offset;",
                                    ]
                                )->toArray();
                          
    return $response;
}

public function getGamesModes() { 
    $response = $this->httpClient->request('POST','https://api.igdb.com/v4/game_modes',
                                    [
                                        'headers' => 
                                            ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                        'body' => 'fields name, slug, url;'
                                    ]
                                )->toArray(); 
    return $response;
}

public function getPlatforms($limit = 500) { 
    $response = $this->httpClient->request('POST','https://api.igdb.com/v4/platforms',
                                    [
                                    'headers' => 
                                        ['Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token],
                                    'body' => 'fields name, slug, url;
                                    limit '."$limit;"
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
                        'body' => 
                            'where status = null;
                            where total_rating != null;'
                            
                    ]
                )->toArray();
    
    return $response['count'];
}

public function countCompanies() {

    $response = $this->httpClient->request(
                    'POST','https://api.igdb.com/v4/companies/count',
                    [
                        'headers' => [
                                'Client-ID' => $this->client, 'Authorization' => 'Bearer '.$this->access_token
                            ],
                        'body' => 
                            'where developed != null;'
                    ]
                )->toArray();
    
    return $response['count'];
}


public function countCharacters() {

    $response = $this->httpClient->request(
                    'POST','https://api.igdb.com/v4/characters/count',
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


// public function CompanyMap() {
//     $response = $this->getCompanies();
//     foreach ($response as $key => $data){
//         if(key_exists('developed',$data)) {
            
//             $response[$key]['game'] = $response[$key]['developed'];
//             unset( $response[$key]['developed']);
//         }
//     }
//     dd($response);
// }

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