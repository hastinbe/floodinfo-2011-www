<?php
/*
TODO:
INSERT INTO  `test`.`county` (`id` , `name`) VALUES (NULL ,  'Cass');
INSERT INTO `test`.`resource` (`id`, `location_id`, `name`, `url`, `twitter_url`, `facebook_url`) VALUES (NULL, '3', 'F-M Area Diversion', 'http://www.fmdiversion.com/', 'https://twitter.com/fmdiversion', NULL);

http://water.weather.gov/resources/hydrographs/biwn8_record.png
http://water.weather.gov/resources/hydrographs/biwn8_hg.png
http://water.weather.gov/ahps2/download_gauge.php?wfo=bis&gage=biwn8&view=1,1,1,1,1,1,1,1&toggles=10,7,8,2,9,15,6
Current stage-discharge rating:
  http://waterdata.usgs.gov/nwisweb/data/ratings/exsa/USGS.06342500.exsa.rdb --- Maps cfs to ft and ft to cfs
*/
require_once 'Bootstrap.php';
//require_once 'constants.php';
include_once 'functions.php';

//$hydrograph = Flood_Hydrograph_Parser_Xml::toArray(PATH_HYDROGRAPH);
$counties = new Flood_Model_County();
$counties = $counties->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>ND Flood - Information</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Language" content="en-US" />
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9">
  <meta name="description" content="Find address elevations in Burleigh and Morton counties for 2011 flood" />
  <meta name="keywords" content="bismarck, burleigh, mandan, morton, missouri, flood, elevation, lookup, address" />
  <link href="css/base.css" media="screen" rel="stylesheet" type="text/css" />
  <link href="css/smoothness/jquery-ui-1.8.13.custom.css" media="all" rel="stylesheet" type="text/css" />
  <!--[if IE]><script type="text/javascript" src="js/excanvas.js"></script><![endif]-->
</head>
<body>
  <div id="body-wrapper">
    <div id="content-wrapper">
      <div id="side-content">
        <div id="alerts-placeholder">
          <div class="loading-sidecontent-container">
            <span class="ui-icon-loading"></span><div>Loading Alerts...</div>
            <div class="clear"></div>
          </div>
        </div>

        <div id="twitter-placeholder">
          <div class="loading-sidecontent-container">
            <span class="ui-icon-loading"></span><div>Loading Twitter...</div>
            <div class="clear"></div>
          </div>
        </div>

        <?php if (LOCATION == 'bisman' || LOCATION == 'fargo-moorhead'): ?>
        <div id="cameras-placeholder">
          <div class="loading-sidecontent-container">
            <span class="ui-icon-loading"></span><div>Loading Cameras...</div>
            <div class="clear"></div>
          </div>
        </div>
        <?php endif ?>

        <?php if (LOCATION == 'bisman'): ?>
        <div id="streams-placeholder">
          <div class="loading-sidecontent-container">
            <span class="ui-icon-loading"></span><div>Loading Streams...</div>
            <div class="clear"></div>
          </div>
        </div>
        <?php endif ?>

        <div id="resources-placeholder">
          <div class="loading-sidecontent-container">
            <span class="ui-icon-loading"></span><div>Loading Resources...</div>
            <div class="clear"></div>
          </div>
        </div>

      </div>

      <div id="content">
        <div class="form-wrapper form-style" style="margin-bottom:2px;width:700px">
          <span><b>Location:</b> <a href="?loc=bisman">Bismarck-Mandan</a> | <a href="?loc=minot">Minot</a> | <a href="?loc=fargo-moorhead">Fargo-Moorhead</a></span>
        </div>

        <div id="advisories-placeholder">
          <div class="loading-sidecontent-container">
            <span class="ui-icon-loading"></span><div>Loading Advisories...</div>
          </div>
        </div>

        <?php if (LOCATION == 'bisman'): ?>
        <div class="form-wrapper form-style" style="float:left">
          <h1>Address Elevation Lookup</h1>
          <p class="description">No degree or order of accuracy has been assigned to data used in this application. We assume no responsibility for the accuracy or completeness of the information or any errors or omission and makes no warranties, express or implied, concerning accuracy, completeness, reliability, or suitability of this data. This data should not be relied upon as the sole basis for solving a problem whose incorrect solution could result in injury to person or property.<br/><br/>
          <strong style="color:orange">Note 1:</strong> Addresses above 1650 feet are not included as they are not projected to be impacted by flooding according to USACE<br/>
          <strong style="color:orange">Note 2:</strong> Water Depth represents the level of inundation at this location, in feet, at the maximum projected flow rate (150,000 CFS) from the Garrison Dam
          </p>

          <form enctype="application/x-www-form-urlencoded" action="" method="post" id="lookup-form" onsubmit="return false">
            <div>
              <label id="label-county" for="county"><p class="label">County<span class="small">Example: Burleigh</span></p></label>
              <select id="county" name="county">
              <?php foreach ($counties as $county): ?>
                <option value="<?= $county->id ?>"><?= $county->name ?></option>
              <?php endforeach ?>
              </select>
              <div class="clear"></div>

              <label id="label-address" for="address"><p class="label">Address <span class="small">Example: 221 N 5TH ST</span></p></label>
              <input type="text" name="address" id="address" value="" maxlength="64" />
              <div class="clear"></div>

              <div style="margin-left:42%;margin-bottom:0.6em">
                <p>
                  <span id="more-options-arrow" class="ui-icon ui-icon-triangle-1-e" style="float:left;margin-right:.3em"></span>
                  <a id="toggle-more-options" href="#">More search options</a>
                </p>
              </div>
              <div class="clear"></div>

              <div id="more-options">
                <label id="label-elevation" for="elevation"><p class="label">Elevation <span class="small">Example: 1630.00</span></p></label>
                <input type="text" name="elevation" id="elevation" value="" maxlength="13" />
                <div class="clear"></div>

                <label id="label-levee" for="levee"><p class="label">Flood Protection<span class="small">Must use with an address</span></p></label>
                <select id="levee" name="levee">
                  <option value=""></option>
                  <option value="YES">Yes</option>
                  <option value="NO">No</option>
                </select>
                <div class="clear"></div>

                <label id="label-water_depth" for="water_depth"><p class="label">Water Depth <span class="small">Example: 8.00</span></p></label>
                <input type="text" name="water_depth" id="water_depth" value="" maxlength="13" />
                <div class="clear"></div>
              </div>

              <div id="loading" style="margin-left:auto;margin-right:auto;width:120px;padding-bottom:10px;display:none">
                <span id="icon-loading" class="ui-icon-loading"></span>
                <div style="float:left;margin-top:7px">Please wait...</div>
                <div class="clear"></div>
              </div>

              <div id="results">
                <table>
                  <thead><tr><th>Address</th><th>Elevation</th><th>Flood Protection</th><th>Water Depth</th></tr></thead>
                  <tbody><tr><td></td></tr></tbody>
                </table>
              </div>
              <div id="noresults" class="ui-widget" style="width:100%;display:none">
                <div class="ui-state-highlight ui-corner-all" style="padding: 0.5em 0;">
                  <p style="margin-left:auto;margin-right:auto;width:50%"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                  <strong>No results found. Try broadening your search</strong></p>
                </div>
              </div>
            </div>
          </form>
          <br/>
          <p class="small-info">Last Updated: 2011/06/02 from <a href="http://bismarck.org/CivicAlerts.aspx?AID=789">Burleigh</a> and <a href="http://www.co.morton.nd.us/index.asp?Type=B_BASIC&SEC={42B40818-3042-4674-99FE-D8C2A6BC1398}&DE={65DBCDDF-85AF-4843-AF04-1126221FBD0C}">Morton</a> County Addresses With Elevation</p>
        </div>
        <?php endif ?>

        <div id="hydrograph-placeholder">
          <div class="loading-sidecontent-container">
            <span class="ui-icon-loading"></span><div>Loading Hydrograph...</div>
          </div>
        </div>

        <div class="clear"></div>
      </div>
    </div>
  </div>

  <div id="footer-wrapper">
    <div>&copy; Copyright 2011-<?= date('Y') ?> <a href="mailto:beausy@gmail.com">Beau Hastings</a> of <a href="http://www.zoovio.com/">Zoovio Inc</a>.</div>
  </div>

  <script type="text/javascript" src="js/jquery-1.5.2.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
  <script type="text/javascript" src="js/dygraph-combined.js"></script>
  <script type="text/javascript" src="js/js.php?loc=<?= LOCATION ?>" defer="defer"></script>
  <script type="text/javascript">
<?php if (APP_ENV == 'production'): ?>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20746667-2']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl':'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
<?php endif ?>
  </script>
</body>
</html>