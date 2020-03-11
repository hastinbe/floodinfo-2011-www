<?php
require_once 'Bootstrap.php';
?>

<?php if (LOCATION == 'bisman'): ?>
<div class="module-container" style="margin-top:1px">
  <h1>Live Streams</h1>
  <object align="middle" id="MediaPlayer" width="480" height="316" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"><param name="URL" value="mms://24.111.15.98:8080/" /><param name="FileName" value="Government Access - Channel 2" /><param name="currentPosition" value="1" /><param name="ShowControls" value="1" /><param name="ShowDisplay" value="0" /><param name="uiMode" value="full" /><param name="ShowStatusBar" value="1" /><param name="stretchToFit" value="1" /><param name="AutoStart" value="1" /><embed type="application/x-mplayer2" src="mms://24.111.15.98:8080/" name="MediaPlayer" width="480" height="316" ShowControls="1" ShowStatusBar="0" ShowDisplay="0" autostart="0"></embed></object>
  <span class="small-info">* Courtesy of <a href="http://www.dakotamediaaccess.org/">Dakota Media Access</a></span>
</div>
<?php elseif (LOCATION == 'minot'): ?>
<div class="module-container" style="margin-top:1px">
  <h1>Live Streams</h1>
  <object width="480" height="386" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param name="flashvars" value="cid=3958274&amp;autoplay=false">
<param name="allowfullscreen" value="true">
<param name="allowscriptaccess" value="always">
<param name="src" value="http://www.ustream.tv/flash/viewer.swf"><embed flashvars="cid=3958274&amp;autoplay=false" width="480" height="386" allowfullscreen="true" allowscriptaccess="always" src="http://www.ustream.tv/flash/viewer.swf" type="application/x-shockwave-flash"></embed></object>
  <span class="small-info">* Courtesy of <a href="http://www.kxnet.com/">KX Weather Channel</a></span>
</div>
<?php endif ?>