<?php
require_once 'Bootstrap.php';
include_once 'functions.php';

loadFeeds(array(
  URL_ALERTS       => array('path' => PATH_ALERTS,       'expires' => MAX_AGE_ALERTS,       'timeout' => 5),
  URL_OBSERVATIONS => array('path' => PATH_OBSERVATIONS, 'expires' => MAX_AGE_OBSERVATIONS, 'timeout' => 5),
));

$alerts       = null;
$observations = null;
try { $alerts       = Zend_Feed::importFile(PATH_ALERTS);       } catch (Exception $e) {}
try { $observations = Zend_Feed::importFile(PATH_OBSERVATIONS); } catch (Exception $e) {}
?>
<?php if (count($alerts) || count($observations)): ?>
<div class="module-container">
  <h1>Alerts &amp; Observations</h1>
  <div id="alerts" style="font-size:0.8em">
  <?php foreach ($alerts as $alert): ?>
    <h3><a href="<?= $alert->link() ?>"><?= (strlen($alert->title()) > 65 ? substr($alert->title(), 0, 65) . '...' : $alert->title()) ?></a></h3>
    <div style="font-size:0.8em"><p><?= $alert->description() ?></p></div>
    <br/>
  <?php endforeach ?>
  <?php foreach ($observations as $observation): ?>
    <h3><a href="<?= $observation->link() ?>"><?= (strlen($observation->title()) > 65 ? substr($observation->title(), 0, 65) . '...' : $observation->title()) ?></a></h3>
    <div style="font-size:0.8em"><p><?= $observation->description() ?></p></div>
    <br/>
  <?php endforeach ?>
  </div>
</div>
<?php endif ?>