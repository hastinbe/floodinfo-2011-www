<?php
// Constants
defined('APP_ENV')  || define('APP_ENV',  (getenv('APP_ENV') ? getenv('APP_ENV') : 'development'));
defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__)));

// Cardinalities
defined('MAX_RESULTS')               || define('MAX_RESULTS',               1000);
defined('MAX_AGE_ADVISORIES')        || define('MAX_AGE_ADVISORIES',        10);   // In minutes
defined('MAX_AGE_ALERTS')            || define('MAX_AGE_ALERTS',            10);   // In minutes
defined('MAX_AGE_MISSOURI_CAMERA')   || define('MAX_AGE_MISSOURI_CAMERA',   10);   // In minutes
defined('MAX_AGE_EXPRESSWAY_CAMERA') || define('MAX_AGE_EXPRESSWAY_CAMERA', 5);    // In minutes
defined('MAX_AGE_ZOOLEVEE_CAMERA')   || define('MAX_AGE_ZOOLEVEE_CAMERA',   5);    // In minutes
defined('MAX_AGE_HYDROGRAPH')        || define('MAX_AGE_HYDROGRAPH',        30);   // In minutes
defined('MAX_AGE_OBSERVATIONS')      || define('MAX_AGE_OBSERVATIONS',      30);   // In minutes
defined('MAX_AGE_RIVERWATCH')        || define('MAX_AGE_RIVERWATCH',        30);   // In minutes
defined('MAX_AGE_TWITTER')           || define('MAX_AGE_TWITTER',           5);    // In minutes

defined('MAX_AGE_REDRIVER_CAMERA')   || define('MAX_AGE_REDRIVER_CAMERA',   10);   // In minutes
defined('MAX_AGE_CITYOFFARGO_CAMERA1')|| define('MAX_AGE_CITYOFFARGO_CAMERA1', 5); // In minutes

if (LOCATION == 'bisman')
{
// URLs to resources
defined('URL_ADVISORIES')        || define('URL_ADVISORIES',       'http://alerts.weather.gov/cap/wwaatmget.php?x=NDC015');
defined('URL_ALERTS')            || define('URL_ALERTS',           'http://water.weather.gov/ahps2/rss/alert/biwn8.rss');
defined('URL_MISSOURI_CAMERA')   || define('URL_MISSOURI_CAMERA',  'https://webcam.crrel.usace.army.mil/bismarck/bismarck1.jpg');
defined('URL_EXPRESSWAY_CAMERA') || define('URL_EXPRESSWAY_CAMERA','http://24.230.95.202/axis-cgi/jpg/image.cgi?resolution=CIF&camera=1');
defined('URL_ZOOLEVEE_CAMERA')   || define('URL_ZOOLEVEE_CAMERA',  'http://24.230.95.202/axis-cgi/jpg/image.cgi?resolution=CIF&camera=3');
defined('URL_HYDROGRAPH')        || define('URL_HYDROGRAPH',       'http://water.weather.gov/ahps2/hydrograph_to_xml.php?gage=biwn8&output=xml');
defined('URL_OBSERVATIONS')      || define('URL_OBSERVATIONS',     'http://water.weather.gov/ahps2/rss/obs/biwn8.rss');
defined('URL_RIVERWATCH')        || define('URL_RIVERWATCH',       'http://us.vocuspr.com/Publish/520028/PRAssetNWORiverwatch.xml');
// File system paths to resource
defined('PATH_ADVISORIES')        || define('PATH_ADVISORIES',        APP_PATH . '/data/advisories.xml');
defined('PATH_ALERTS')            || define('PATH_ALERTS',            APP_PATH . '/data/alerts.xml');
defined('PATH_MISSOURI_CAMERA')   || define('PATH_MISSOURI_CAMERA',   APP_PATH . '/images/bismarck1.jpg');
defined('PATH_EXPRESSWAY_CAMERA') || define('PATH_EXPRESSWAY_CAMERA', APP_PATH . '/images/expressway1.jpg');
defined('PATH_ZOOLEVEE_CAMERA')   || define('PATH_ZOOLEVEE_CAMERA',   APP_PATH . '/images/zoolevee1.jpg');
defined('PATH_HYDROGRAPH')        || define('PATH_HYDROGRAPH',        APP_PATH . '/data/hydrograph.xml');
defined('PATH_OBSERVATIONS')      || define('PATH_OBSERVATIONS',      APP_PATH . '/data/observations.xml');
defined('PATH_RIVERWATCH')        || define('PATH_RIVERWATCH',        APP_PATH . '/data/riverwatch.xml');
defined('PATH_TWITTER')           || define('PATH_TWITTER',           APP_PATH . '/data/cache-twitter');

defined('NAME_HYDROGRAPH') || define('NAME_HYDROGRAPH', 'Missouri River at Bismarck');
defined('INFO_HYDROGRAPH') || define('INFO_HYDROGRAPH', 'Gaging station located on left bank, 2,100 feet downstream from Burlington Northern Railway bridge');
}
elseif (LOCATION == 'minot')
{
// URLs to resources
defined('URL_ADVISORIES')        || define('URL_ADVISORIES',       'http://alerts.weather.gov/cap/wwaatmget.php?x=NDC101');
defined('URL_ALERTS')            || define('URL_ALERTS',           'http://water.weather.gov/ahps2/rss/alert/mion8.rss');
defined('URL_MISSOURI_CAMERA')   || define('URL_MISSOURI_CAMERA',  'https://webcam.crrel.usace.army.mil/bismarck/bismarck1.jpg');
defined('URL_EXPRESSWAY_CAMERA') || define('URL_EXPRESSWAY_CAMERA','http://24.230.95.202/axis-cgi/jpg/image.cgi?resolution=CIF&camera=1');
defined('URL_ZOOLEVEE_CAMERA')   || define('URL_ZOOLEVEE_CAMERA',  'http://24.230.95.202/axis-cgi/jpg/image.cgi?resolution=CIF&camera=3');
defined('URL_HYDROGRAPH')        || define('URL_HYDROGRAPH',       'http://water.weather.gov/ahps2/hydrograph_to_xml.php?gage=mion8&output=xml');
defined('URL_OBSERVATIONS')      || define('URL_OBSERVATIONS',     'http://water.weather.gov/ahps2/rss/obs/mion8.rss');
defined('URL_RIVERWATCH')        || define('URL_RIVERWATCH',       'http://us.vocuspr.com/Publish/520028/PRAssetNWORiverwatch.xml');
// File system paths to resource
defined('PATH_ADVISORIES')        || define('PATH_ADVISORIES',        APP_PATH . '/data/advisories.minot.xml');
defined('PATH_ALERTS')            || define('PATH_ALERTS',            APP_PATH . '/data/alerts.minot.xml');
defined('PATH_HYDROGRAPH')        || define('PATH_HYDROGRAPH',        APP_PATH . '/data/hydrograph.minot.xml');
defined('PATH_OBSERVATIONS')      || define('PATH_OBSERVATIONS',      APP_PATH . '/data/observations.minot.xml');
defined('PATH_RIVERWATCH')        || define('PATH_RIVERWATCH',        APP_PATH . '/data/riverwatch.minot.xml');
defined('PATH_TWITTER')           || define('PATH_TWITTER',           APP_PATH . '/data/cache-twitter-minot');

defined('NAME_HYDROGRAPH') || define('NAME_HYDROGRAPH', 'Souris River at Minot Broadway Bridge');
defined('INFO_HYDROGRAPH') || define('INFO_HYDROGRAPH', '');
}
elseif (LOCATION == 'fargo-moorhead')
{
// URLs to resources
defined('URL_ADVISORIES')        || define('URL_ADVISORIES',              'http://alerts.weather.gov/cap/wwaatmget.php?x=NDC017');
defined('URL_ALERTS')            || define('URL_ALERTS',                  'http://water.weather.gov/ahps2/rss/alert/fgon8.rss');
defined('URL_HYDROGRAPH')        || define('URL_HYDROGRAPH',              'http://water.weather.gov/ahps2/hydrograph_to_xml.php?gage=fgon8&output=xml');
defined('URL_OBSERVATIONS')      || define('URL_OBSERVATIONS',            'http://water.weather.gov/ahps2/rss/obs/fgon8.rss');
//defined('URL_RIVERWATCH')        || define('URL_RIVERWATCH',            'http://us.vocuspr.com/Publish/520028/PRAssetNWORiverwatch.xml');
// File system paths to resource
defined('URL_REDRIVER_CAMERA')    || define('URL_REDRIVER_CAMERA',        'http://nd.water.usgs.gov/FARGOwebcam/webcama.JPG');
defined('URL_CITYOFFARGO_CAMERA1')|| define('URL_CITYOFFARGO_CAMERA1',    'http://webcam1.cityoffargo.com/jpg/image.jpg');
defined('PATH_ADVISORIES')        || define('PATH_ADVISORIES',            APP_PATH . '/data/advisories.fargo.xml');
defined('PATH_ALERTS')            || define('PATH_ALERTS',                APP_PATH . '/data/alerts.fargo.xml');
defined('PATH_HYDROGRAPH')        || define('PATH_HYDROGRAPH',            APP_PATH . '/data/hydrograph.fargo.xml');
defined('PATH_OBSERVATIONS')      || define('PATH_OBSERVATIONS',          APP_PATH . '/data/observations.fargo.xml');
//defined('PATH_RIVERWATCH')        || define('PATH_RIVERWATCH',          APP_PATH . '/data/riverwatch.fargo.xml');
defined('PATH_REDRIVER_CAMERA')   || define('PATH_REDRIVER_CAMERA',       APP_PATH . '/images/redriver.jpg');
defined('PATH_CITYOFFARGO_CAMERA1') || define('PATH_CITYOFFARGO_CAMERA1', APP_PATH . '/images/cityoffargo-cam1.jpg');
defined('PATH_TWITTER')           || define('PATH_TWITTER',               APP_PATH . '/data/cache-twitter-fargo');

defined('NAME_HYDROGRAPH') || define('NAME_HYDROGRAPH', 'Red River of the North at Fargo');
defined('INFO_HYDROGRAPH') || define('INFO_HYDROGRAPH', '');
}
// Twitter API
/*defined('TWITTER_API_KEY')                 || define('TWITTER_API_KEY', 'xxwZceRE0fU2Hyp3X0lCMQ');
defined('TWITTER_OAUTH_CONSUMER_KEY')      || define('TWITTER_OAUTH_', 'xxwZceRE0fU2Hyp3X0lCMQ');
defined('TWITTER_OAUTH_CONSUMER_SECRET')   || define('TWITTER_OAUTH_', 'xJp6oqZHaKPNdw3Wu0jWRAkgSaEMRhrwZiEQm5S06k');
defined('TWITTER_OAUTH_REQUEST_TOKEN_URL') || define('TWITTER_OAUTH_', 'https://api.twitter.com/oauth/request_token');
defined('TWITTER_OAUTH_ACCESS_TOKEN_URL')  || define('TWITTER_OAUTH_', 'https://api.twitter.com/oauth/access_token');
defined('TWITTER_OAUTH_AUTHORIZE_URL')     || define('TWITTER_OAUTH_', 'https://api.twitter.com/oauth/authorize');*/