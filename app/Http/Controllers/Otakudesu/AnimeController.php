<?php

namespace App\Http\Controllers\Otakudesu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class AnimeController extends Controller
{

	private $api_url = "https://otakudesu.info/wp-json/wp/v2/";

	private $base_url = "https://otakudesu.info/";

	private $d = "posts?_fields[]=slug&_fields[]=title.rendered";

	protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();

    }

   	
   	public function latest(){
   		
   		$res = $this->client->get($this->api_url . 'posts?_fields[]=slug&_fields[]=title.rendered');

   		return htmlspecialchars($res->getBody());
   	}


   	public function search(Request $request)
   	{
   		$res = $this->client->get(
   			$this->base_url . '?post_type=anime&s='.$request->input('q')
   		);

   		if($res->getStatusCode() != 200){
   			return response()->json([
   				'status' => false
   			]);
   		}

   		$crawler = new Crawler($res->getBody());

   		$animes = [];

   		$animesDOM = $crawler->filter('#venkonten > div > div.venser > div > div > ul > li');

   		foreach ($animesDOM as $anime) {
   			
   			$temp = [];
   			$temp['poster'] = $anime->filter('img')->attr('src');

   			$animes[] = $temp;  
   		}

		// return $res->getStatusCode();

   		return dd($animes);
   	}


}
