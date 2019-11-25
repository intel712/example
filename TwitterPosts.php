<?php

namespace common\components\social;

use yii\base\Component;
use common\components\social\TwitterApi;

class TwitterPosts extends Component {

    
    public function getTwitterPosts($screen_name, $count = 18)
    {
        $settings = [
            'oauth_access_token' => "access token there",
            'oauth_access_token_secret' => "token secret there",
            'consumer_key' => "consumer key there",
            'consumer_secret' => "consumer secret there"
        ];

        //Yii::$app->response->format = Response::FORMAT_RAW;

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name=' . $screen_name . '&count=' . $count;
        $requestMethod = 'GET';

        $twitter = new TwitterApi($settings);

        return $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
    }
    
}
