<?php

//---------------------------------------------------------
//  Retrieves keywords for an event hashtag using MonkeyLearn
//  https://developers.triathlon.org/docs/live-twitter-feed
//---------------------------------------------------------

require_once('vendor/autoload.php');
require_once('config.php');

use MonkeyLearn\Client;
use Unirest\Request;

// Initialize some variables
$tweets = [];
$page = 1;
$last_page = 1;

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

// All out Tweets are now in the $tweets array so we can do with as we please. In this instance we will implode to a single string for analysis
// Need to ensure that such a string is under the text limit of circa 800,000 chars
$text = implode(' ', $tweets);

// Send this concatenated Tweet list to MonkeyLearn using the keywords public module https://app.monkeylearn.com/main/extractors/ex_y7BPYzNG/
$ml = new Client(MONKEYLEARN);
$text_list = [$text];
$module_id = 'ex_y7BPYzNG';
$result = $ml->extractors->extract($module_id, $text_list);

// Format our output with some headers
echo "Keyword | Count | Relevance \r\n";

// We have only sent a single block of text so simply loop through the returned array to get the 10 keywords returned
foreach($result->result[0] as $keywords) {
    echo "{$keywords['keyword']} | {$keywords['count']} | {$keywords['relevance']} \r\n";
}
