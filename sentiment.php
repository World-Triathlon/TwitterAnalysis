<?php

//-----------------------------------------------------------------
//  Performs sentiment analysis via MonkeyLearn for a set of Tweets
//  https://developers.triathlon.org/docs/live-twitter-feed
//-----------------------------------------------------------------

require_once('vendor/autoload.php');
require_once('config.php');

use MonkeyLearn\Client;
use Unirest\Request;

// Initialize some variables
$tweets = [];
$negative_tweets = [];
$positive_tweets = [];
$neutral_tweets = [];
$page = 1;
$last_page = 1;
$positive  = 0;
$negative = 0;
$neutral = 0;
$i=0;

// Set up our standard request (verifyPeer only needed if running locally without SSL)
Request::defaultHeader('apikey', APIKEY);
Request::verifyPeer(false);

// API will return a paginated response so loop through each page
while ($page <= $last_page) {

    $request = Request::get("https://api.triathlon.org/v1/live/twitter?exclude_retweets=true&per_page=100&filter=" . HASHTAG . "&page={$page}")->body;

    // Let's get the total number of pages to loop through
    if($page === 1) $last_page = $request->last_page;

    foreach($request->data as $tweet) {
        // Get the tweet and strip it of HTML tags
        array_push($tweets, strip_tags($tweet->tweet));
    }

    // Increment the page to get the next page of results
    $page++;
}

$ml = new Client(MONKEYLEARN);
$module_id = 'cl_qkjxv9Ly';
$result = $ml->classifiers->classify($module_id, $tweets, true);

// We have the completed sentiment analysis so loop through each
foreach($result->result as $r) {

    foreach($r as $sentiment) {

        // Each tweet will have been labelled as positive, negative or neutral
        // Increment each label counter and store the tweet so we can see examples of each
        switch($sentiment['label']) {
            case "positive":
                $positive++;
                array_push($positive_tweets, $tweets[$i]);
                break;
            case "negative":
                $negative++;
                array_push($negative_tweets, $tweets[$i]);
                break;
            case "neutral":
                $neutral ++;
                array_push($neutral_tweets, $tweets[$i]);
                break;
        }
    }

    $i++;
}

// Output the results of the analysis
echo "Positive: {$positive} \r\n";
echo "Negative: {$negative} \r\n";
echo "Neutral: {$neutral} \r\n";

// For additional analysis we can also output each of the tweets from each label to find examples e.g. for
// negative_tweets
echo "Negative Tweet Results \r\n";

foreach($negative_tweets as $tweet) {
    echo "{$tweet} \r\n";
}
