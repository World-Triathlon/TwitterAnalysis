<?php

//---------------------------------------------------------
//  Counts the number of times a hashtag has occurred
//  https://developers.triathlon.org/docs/live-twitter-feed
//---------------------------------------------------------

require_once('vendor/autoload.php');
require_once('config.php');

use Unirest\Request;

// Send the request
Request::defaultHeader('apikey', APIKEY);
Request::verifyPeer(false);
$request = Request::get("https://api.triathlon.org/v1/live/twitter?exclude_retweets=true&filter=" . HASHTAG . "&per_page=1&page=1")->body;

echo $request->total;
