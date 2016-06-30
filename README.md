## Triathlon API Twitter Keyword and Sentiment Analysis with MonkeyLearn.

This is a quick and dirty script to quickly run keyword and sentiment analysis of event tweets via the [Triathlon API]
(https://developers.triathlon.org) and [MonkeyLearn](http://monkeylearn.com/). It is in no way meant for production usage.

* Step 1: Get a Triathlon API key from the [Triathlon API Management Portal](https://apps.api.triathlon.org/register)
* Step 2: Get a Monkey Learn API key from [here](https://app.monkeylearn.com/accounts/register/)
* Step 3: Clone this repo and replace config.php with your API keys obtained in steps 1 & 2 and the event hashtag you wish
 to analyse (all events may not be available)
* Step 4a: Download [composer](https://getcomposer.org/download/) if not on your local system
* Step 4b: Run `composer install`
* Step 5: Run the script on the command line with `php -f keywords.php` to output the results of the keyword analysis

The result of the keyword analysis will produce the top 10 keywords, the number of times they occurred and the
relevance (1 being the highest). Note that this analysis was performed on a very small number of tweets for an example.

```
Keyword | Count | Relevance
weekend | 13 | 0.984
Saturday | 14 | 0.849
American Sarah True | 2 | 0.816
athletes | 9 | 0.751
RACE WEEK | 3 | 0.734
WTS schedule | 3 | 0.734
Alistair Brownlee | 3 | 0.734
WTS podiums | 3 | 0.734
Sweden Stockholm | 3 | 0.734
Jonathan Brownlee | 3 | 0.734
```

You may also run a sentiment analysis `php -f sentiment.php` which will yield a result similar to the following:

```
Positive: 23
Negative: 1
Neutral: 62
Negative Tweet Results
Unfortunately due to illness @Jgomeznoya will not compete in #WTSStockholm this wknd. Wishing him a speedy recovery! https://t.co/Tg2Bl2cnTO
```


