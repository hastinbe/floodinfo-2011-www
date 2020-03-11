<?php
require_once 'Bootstrap.php';
include_once 'functions.php';

if (LOCATION == 'bisman')
{
  loadFeeds(array(
    URL_RIVERWATCH => array('path' => PATH_RIVERWATCH, 'expires' => MAX_AGE_RIVERWATCH, 'timeout' => 5),
  ));

  $riverwatch   = null;
  try { $riverwatch = Zend_Feed::importFile(PATH_RIVERWATCH); } catch (Exception $e) {}

  $resources = new Flood_Model_Resource();
  $resources = $resources->fetchAll(true, array('location_id = ?', 1));
}
elseif (LOCATION == 'minot') {
  $resources = new Flood_Model_Resource();
  $resources = $resources->fetchAll(true, array('location_id = ?', 2));
}
elseif (LOCATION == 'fargo-moorhead') {
  $resources = new Flood_Model_Resource();
  $resources = $resources->fetchAll(true, array('location_id = ?', 3));
}
?>
<div class="module-container" style="margin-top:1px">
  <h1>Resources</h1>
  <ul>
  <?php if (isset($riverwatch)): ?>
    <?php foreach($riverwatch as $item): ?>
      <?php if (strtotime($item->pubDate()) >= strtotime('-2 days')): ?>
        <li><a href="<?= $item->link() ?>"><?= $item->title() ?></a></li>
      <?php endif ?>
    <?php endforeach ?>
  <?php endif ?>
  <?php foreach($resources as $resource): ?>
    <li>
      <a href="<?= $resource->url ?>"><?= $resource->name ?></a>
      <?php if (!empty($resource->twitter_url)): ?>
      <a href="<?= $resource->twitter_url ?>" title="<?= $resource->name ?> on Twitter"><img src="images/social-networks/twitter.png" width="16" height="16" alt="Twitter"/></a>
      <?php endif ?>
      <?php if (!empty($resource->facebook_url)): ?>
      <a href="<?= $resource->facebook_url ?>" title="<?= $resource->name ?> on Facebook"><img src="images/social-networks/facebook.gif" width="16" height="16" alt="Facebook"/></a>
      <?php endif ?>
    </li>
  <?php endforeach ?>
  </ul>
</div>