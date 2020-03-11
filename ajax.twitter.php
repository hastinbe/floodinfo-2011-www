<?php
require_once 'Bootstrap.php';
require_once 'functions.php';

if (LOCATION == 'bisman')
{
  $searches = array(
    'bisflood'      => array('search' => '#bisflood',          'path' => PATH_TWITTER . '-bisflood',      'expires' => MAX_AGE_TWITTER, 'max_results' => 5),
    'USGSND'        => array('search' => 'from:USGSND',        'path' => PATH_TWITTER . '-usgsnd',        'expires' => MAX_AGE_TWITTER, 'max_results' => 5),
    'BismarckNDGov' => array('search' => 'from:BismarckNDGov', 'path' => PATH_TWITTER . '-bismarckndgov', 'expires' => MAX_AGE_TWITTER, 'max_results' => 5),
    'BurleighEM'    => array('search' => 'from:BurleighEM',    'path' => PATH_TWITTER . '-burleighem',    'expires' => MAX_AGE_TWITTER, 'max_results' => 5)
  );
}
elseif (LOCATION == 'minot')
{
  $searches = array(
    'minotflood'    => array('search' => '#minotflood',        'path' => PATH_TWITTER . '-minotflood',    'expires' => MAX_AGE_TWITTER, 'max_results' => 5),
    'USGSND'        => array('search' => 'from:USGSND',        'path' => PATH_TWITTER . '-usgsnd',        'expires' => MAX_AGE_TWITTER, 'max_results' => 5),
    'robport'       => array('search' => 'from:robport',       'path' => PATH_TWITTER . '-robport',       'expires' => MAX_AGE_TWITTER, 'max_results' => 5),
    //'BismarckNDGov' => array('search' => 'from:BismarckNDGov', 'path' => PATH_TWITTER . '-bismarckndgov', 'expires' => MAX_AGE_TWITTER, 'max_results' => 5),
    //'BurleighEM'    => array('search' => 'from:BurleighEM',    'path' => PATH_TWITTER . '-burleighem',    'expires' => MAX_AGE_TWITTER, 'max_results' => 5)
  );
}

$twitter_searches = array();
/* Disabled due to issue with Zend_Service_Twitter_Search throwing exception:
Fatal error: Uncaught exception 'Zend_Http_Client_Adapter_Exception' with message 'Invalid chunk size "" unable to read chunked body' in D:\Applications\Apache2.2\htdocs\flood2011\library\Zend\Http\Client\Adapter\Socket.php:369 Stack trace: #0 D:\Applications\Apache2.2\htdocs\flood2011\library\Zend\Http\Client.php(1075): Zend_Http_Client_Adapter_Socket->read() #1 D:\Applications\Apache2.2\htdocs\flood2011\library\Zend\Rest\Client.php(166): Zend_Http_Client->request('GET') #2 D:\Applications\Apache2.2\htdocs\flood2011\library\Zend\Service\Twitter\Search.php(154): Zend_Rest_Client->restGet('/search.json', Array) #3 D:\Applications\Apache2.2\htdocs\flood2011\functions.php(119): Zend_Service_Twitter_Search->search('#bisflood', Array) #4 D:\Applications\Apache2.2\htdocs\flood2011\ajax.twitter.php(25):
$twitter_searches = getTwitterTweets($searches);
*/
?>
<?php if (!empty($twitter_searches)): ?>
<div class="module-container" style="margin-top:1px">
  <h1>Twitter</h1>
  <div id="twitter-tabs">
    <ul>
    <?php foreach ($twitter_searches as $label => $twitter_search): ?>
      <li><a href="#twitter-tab-<?= $label ?>"><?= $label ?></a></li>
    <?php endforeach ?>
    </ul>
    <?php foreach ($twitter_searches as $label => $twitter_search): ?>
    <div id="twitter-tab-<?= $label ?>">
      <p style="padding-top:10px">
      <?php if (!empty($twitter_search['results'])): ?>
        <ul>
        <?php foreach ($twitter_search['results'] as $tweet): ?>
          <li style="font-size:0.7em"><strong><?= $tweet['from_user'] ?></strong> <a href="http://twitter.com/#!/<?= $tweet['from_user'] ?>/status/<?= $tweet['id_str'] ?>" style="text-decoration:under;color:blue"><?= $tweet['text'] ?></a></li>
        <?php endforeach ?>
      </ul>
      <?php else: ?>
        No results found.
      <?php endif ?>
      </p>
    </div>
    <?php endforeach ?>
  </div>
</div>
<?php endif ?>