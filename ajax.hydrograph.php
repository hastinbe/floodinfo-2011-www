<?php
require_once 'Bootstrap.php';
include_once 'functions.php';

loadFeeds(array(
  URL_HYDROGRAPH => array('path' => PATH_HYDROGRAPH, 'expires' => MAX_AGE_HYDROGRAPH, 'timeout' => 5),
));

$hydrograph = Flood_Hydrograph_Parser_Xml::toArray(PATH_HYDROGRAPH);
?>
<div class="form-wrapper form-style" style="margin-top:1px;float:left;width:700px"> 
  <h1><?= NAME_HYDROGRAPH ?></h1><br/>
  <div id="graph_stage"></div><br/>
  <div id="graph_flow"></div><br/>
  <span class="small-info">* Click and drag on graph to zoom, shift and drag to pan, double-click to reset.</span><br/>
  <span class="small-info"><?= INFO_HYDROGRAPH ?></span>
</div>

<script type="text/javascript">
  var lines = [];
  var xline;
  
  stage = new Dygraph(
    document.getElementById("graph_stage"), [
  <?php $num_observed = count($hydrograph['observed']) ?>
  <?php for($i = 0; $i < $num_observed; $i++): ?>
    [new Date(<?= json_encode($hydrograph['observed'][$i][0]) ?>), <?= json_encode($hydrograph['observed'][$i][1]) ?>, null],
  <?php endfor ?>
  
  <?php $num_forecast = count($hydrograph['forecast']) ?>
  <?php for($i = 0; $i < $num_forecast; $i++): ?>
    [new Date(<?= json_encode($hydrograph['forecast'][$i][0]) ?>), null, <?= json_encode($hydrograph['forecast'][$i][1]) ?>]<?= ($num_forecast > $i+1 ? ',' : '') ?>
  <?php endfor ?>
  ],
    {
      height:280,
      width:700,
      connectSeparatedPoints: true,
      fillGraph: true,
      rollPeriod: 7,
      labels: [ "Date", "Observed", "Forecast" ],
      title: "Stage",
      legend: "always",
      ylabel: "feet",

      highlightCallback: function(e, x, pts) {
        for (var i = 0; i < pts.length; i++) {
          var y = pts[i].canvasy;
          lines[i].style.display = "";
          lines[i].style.top = y - 281 + "px";
          if (i == 0) xline.style.left = pts[i].canvasx + "px";
        }
        xline.style.display = "";
        xline.style.top = "-253px";
        xline.style.height = "83%";
      },
      unhighlightCallback: function(e) {
        for (var i = 0; i < 2; i++) {
          lines[i].style.display = "none";
        }
        xline.style.display = "none";
      }   
    }
  );
  
  for (var i = 0; i < 2; i++) {
    var line = document.createElement("div");
    line.style.display = "none";
    line.style.width = "91.3%";
    line.style.left = "56px";
    line.style.height = "1px";
    line.style.backgroundColor = "black";
    line.style.position = "relative";
    document.getElementById("graph_stage").appendChild(line);
    lines.push(line);
  }
  xline = document.createElement("div");
  xline.style.display = "none";
  xline.style.width = "1px";
  xline.style.height = "83%";
  xline.style.top = "-218px";
  xline.style.backgroundColor = "black";
  xline.style.position = "relative";
  document.getElementById("graph_stage").appendChild(xline);

  flow = new Dygraph(
    document.getElementById("graph_flow"), [
  <?php $num_observed = count($hydrograph['observed']) ?>
  <?php for($i = 0; $i < $num_observed; $i++): ?>
    [new Date(<?= json_encode($hydrograph['observed'][$i][0]) ?>), <?= json_encode($hydrograph['observed'][$i][2]) ?>, null],
  <?php endfor ?>
  
  <?php $num_forecast = count($hydrograph['forecast']) ?>
  <?php for($i = 0; $i < $num_forecast; $i++): ?>
    [new Date(<?= json_encode($hydrograph['forecast'][$i][0]) ?>), null, <?= json_encode($hydrograph['forecast'][$i][2]) ?>]<?= ($num_forecast > $i+1 ? ',' : '') ?>
  <?php endfor ?>
  ],
    {
      height:280,
      width:700,
      connectSeparatedPoints: true,
      fillGraph: true,
      rollPeriod: 7,
      labels: [ "Date", "Observed", "Forecast" ],
      title: "Flow",
      legend: "always",
      ylabel: "kcfs",
      highlightCallback: function(e, x, pts) {
        for (var i = 0; i < pts.length; i++) {
          var y = pts[i].canvasy;
          lines[i].style.display = "";
          lines[i].style.top = y + 19 + "px";
          if (i == 0) xline.style.left = pts[i].canvasx + "px";
          xline.style.height = "82%";
        }
        xline.style.display = "";
        xline.style.top = "49px";
      },
      unhighlightCallback: function(e) {
        for (var i = 0; i < 2; i++) {
          lines[i].style.display = "none";
        }
        xline.style.display = "none";
      }
    }
  );
</script>