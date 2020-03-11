<?php
require_once 'Bootstrap.php';
include_once 'functions.php';

// Minot images: http://www.kfyrtv.com/skycam/minotskycam/Skycapture_KM.jpg

if (LOCATION == 'bisman')
{
  loadImages(array(
    URL_MISSOURI_CAMERA => array('path' => PATH_MISSOURI_CAMERA, 'expires' => MAX_AGE_MISSOURI_CAMERA, 'timeout' => 5, 'width' => 480, 'height' => 272),
    // Commented out because we link directly to the live cams and refresh every 15 seconds @see updateCameras() in elevation.js
    //URL_EXPRESSWAY_CAMERA => array('path' => PATH_EXPRESSWAY_CAMERA, 'expires' => MAX_AGE_EXPRESSWAY_CAMERA, 'timeout' => 5, 'width' => 352, 'height' => 240),
    //URL_ZOOLEVEE_CAMERA   => array('path' => PATH_ZOOLEVEE_CAMERA,   'expires' => MAX_AGE_ZOOLEVEE_CAMERA,   'timeout' => 5, 'width' => 352, 'height' => 240),
  ));
}
elseif (LOCATION == 'fargo-moorhead')
{
  loadImages(array(
    //URL_REDRIVER_CAMERA => array('path' => PATH_REDRIVER_CAMERA, 'expires' => MAX_AGE_REDRIVER_CAMERA, 'timeout' => 5, 'width' => 480, 'height' => 272),
    URL_CITYOFFARGO_CAMERA1 => array('path' => PATH_CITYOFFARGO_CAMERA1, 'expires' => MAX_AGE_CITYOFFARGO_CAMERA1, 'timeout' => 5, 'width' => 480, 'height' => 272)
  ));
}
?>
<?php if (LOCATION == 'bisman'): ?>
<div class="module-container" style="margin-top:1px">
  <h1>Cameras</h1>
  <div id="camera-accordion">
    <h3><a href="#">Missouri River from Gauging Station</a></h3>
    <div>
      <p>
        <img id="camera-missouri-gage" src="https://webcam.crrel.usace.army.mil/bismarck/bismarck1.jpg" width="420" height="272" alt="Missouri River from Gaging Station" title="Missouri River from Gaging Station" /><br/>
        <span class="small-info">* Courtesy of <a href="http://usace.army.mil">US Army Corps of Engineers</a></span><br/>
      </p>
    </div>
  </div>
</div>
<?php elseif (LOCATION == 'fargo-moorhead'): ?>
<div class="module-container" style="margin-top:1px">
  <h1>Cameras</h1>
  <div id="camera-accordion">
    <h3><a href="#">Red River from Gauging Station</a></h3>
    <div>
      <p>
        <img id="camera-redriver-fargo" src="http://nd.water.usgs.gov/FARGOwebcam/webcama.JPG" width="420" height="272" alt="Missouri River from Gaging Station" title="Missouri River from Gaging Station" /><br/>
        <span class="small-info">* Courtesy of <a href="http://nd.water.usgs.gov/">USGS</a></span><br/>
      </p>
    </div>
    <h3><a href="#">City of Fargo - Webcam 1</a></h3>
    <div>
      <p>
        <img id="camera-cityoffargo-webcam1" src="http://webcam1.cityoffargo.com/jpg/image.jpg" width="420" height="272" alt="City of Fargo - Webcam 1" title="City of Fargo - Webcam 1" /><br/>
        <span class="small-info">* Courtesy of <a href="http://cityoffargo.com/">City of Fargo</a></span><br/>
      </p>
    </div>

    <h3><a href="#">Red River from Grand Forks <small>(near Sorlie Bridge)</small></a></h3>
    <div>
      <p>
        <img id="camera-redriver-grandforks" src="http://nd.water.usgs.gov/GFwebcam/webcama.JPG" width="420" height="272" alt="Red River from Grand Forks" title="Red River from Grand Forks" /><br/>
        <span class="small-info">* Courtesy of <a href="http://nd.water.usgs.gov/">USGS</a></span><br/>
      </p>
    </div>
  </div>
</div>
<?php endif ?>