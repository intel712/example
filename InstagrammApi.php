<?php


namespace common\components\social;

use InstagramScraper\Instagram;
use InstagramScraper\Exception\InstagramException;


class InstagrammApi {
    
    
    public function getOwnerData($params)
    {
        $result = $params;
        $result['url'] = 'https://instagram.com/'.$params['login'];
        return $result;
    }
    
    
    public function getPhotos($params,$provider='scraper')
    {
        
        $result = [];

        switch ($provider) {
            
            case 'scraper':
                $data = $this->getDataByScraper($params);
                $result = $this->convertDataFromScrapper($data,$params);
                break;
            
        }
        
        return $result;
        
    }
    
    public function convertDataFromScrapper($data,$params)
    {
        $result = [];
        
        $group = 0;
        $group_cnt = 0;
        $max_in_group = 3;
//        echo '<pre>'; print_r($data); echo '</pre>';        
        foreach ($data AS $item) {
            $tmp = [
                'url'   =>      $item->getLink(),
//                'tbnl'  =>      $item->getImageLowResolutionUrl(),
		'tbnl'  =>	 $item->getImageHighResolutionUrl(),
                'title' =>      $item->getOwner()->getFullName(),
                'message'   =>  $this->convertTags($item->getCaption()),
                'alt'   => htmlspecialchars($item->getOwner()->getFullName()),
            ];
            
            //$result[$group]['items'][] = $tmp;
            $result[] = $tmp;
            
            $group_cnt++;
            if ($group_cnt == $max_in_group) {
                $group_cnt = 0;
                $group++;
            }
            
        }
	
        return $result;
        
    }
    
    
    public function convertTags($string)
    {
        //return $string;
        preg_match_all('/#([a-z,0-9]+)/i',$string,$match);
        if (isset($match[1])) {
            foreach ($match[1] AS $tag) {
                $replace = '<a href="https://instagram.com/explore/tags/'.$tag.'" target="_blank">#'.$tag.'</a>';
                $string = preg_replace("/#".$tag."( |})/i",$replace.' ','{'.$string.'}');
            }
            
        }
        
        return trim($string,'{,}');
    }
  
  
  
    
    public function getDataByScraper($params)
    {
        $result = [];
        
        try {
//                $result = Instagram::getMedias($params['login'], $params['limit']);

		$instagram = \InstagramScraper\Instagram::withCredentials('InstaApiLogin', 'InstaApiLogin*1133');
		$instagram->login();
//		$instagram = new \InstagramScraper\Instagram();
		$result = $instagram->getMedias($params['login'], $params['limit']);
        } catch (\Exception $e) {

        }

        return $result;
    }
    
    public function getPhotos0($params)
    {
        
        $iurl = 'http://instagr.am/p/test/';
        $url_main = 'https://api.instagram.com/oembed/?url=';
        
        $url = $url_main.$iurl;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        
        $output = curl_exec($ch);
        
        echo '<pre>';
        print_r(json_decode($output,true));
        echo '</pre>';
        
        exit;
        
        curl_close($ch);
        
        
    }
    
}
