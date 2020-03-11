<?php
require_once 'Bootstrap.php';
include_once 'functions.php';

loadFeeds(array(
  URL_ADVISORIES => array('path' => PATH_ADVISORIES, 'expires' => MAX_AGE_ADVISORIES, 'timeout' => 5),
));

$advisories = null;
try { $advisories = Zend_Feed::importFile(PATH_ADVISORIES); } catch (Exception $e) {}
?>
<?php if (count($advisories)): ?>
<div class="form-wrapper form-style" style="margin-bottom:1px">
  <h1>Advisories, Watches, Warnings</h1>
  <div id="advisories" style="font-size:0.8em">
  <?php $titles = array(); ?>
  <?php foreach ($advisories as $advisory): ?>
  <?php 
    if (in_array($advisory->title(), $titles)) continue;
    $titles[] = $advisory->title();
  ?>
    <h3><a href="<?= $advisory->id() ?>"><?= (strlen($advisory->title()) > 90 ? substr($advisory->title(), 0, 90) . '...' : $advisory->title()) ?></a></h3>
    <div style="font-size:1.2em"><p><?= $advisory->summary() ?></p></div>
    <br/>
  <?php endforeach ?>
  </div>
</div>
<?php endif ?>