<?php
// Source: http://www.fliquidstudios.com/2009/05/07/resizing-images-in-php-with-gd-and-imagick/
function resize_image($file, $w, $h, $crop=FALSE)
{
  list($width, $height) = getimagesize($file);
  $r = $width / $height;
  if ($crop)
  {
    if ($width > $height)
      $width = ceil($width-($width*($r-$w/$h)));
    else
      $height = ceil($height-($height*($r-$w/$h)));

    $newwidth = $w;
    $newheight = $h;
  }
  else {
    if ($w/$h > $r)
    {
      $newwidth = $h*$r;
      $newheight = $h;
    }
    else {
      $newheight = $w/$r;
      $newwidth = $w;
    }
  }
  $src = imagecreatefromjpeg($file);
  $dst = imagecreatetruecolor($newwidth, $newheight);
  imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

  return $dst;
}

/**
 * Check if request method was POST
 *
 * @return boolean
 */
function isPost()
{
  return isset($_SERVER['REQUEST_METHOD'])
            && $_SERVER['REQUEST_METHOD'] == 'POST';
}

/**
 * Downloads feeds when their cache is expired
 *
 * Possible options:
 *    path    - Path to file that is used to cache the data
 *    expires - Time in minutes that the cache expires
 *    timeout - Time to wait for a response when requesting a resource
 *
 * @param   array   $searches   An associative array where the key is the path to a feed, and the value is an array of options
 * @return  null
 */
function loadFeeds(array $feeds)
{
  foreach ($feeds as $feed => $options)
  {
    $timeout_context = stream_context_create(array(
      'http' => array('timeout' => $options['timeout'])
    ));
    
    if (!file_exists($options['path']) || (time() - @filemtime($options['path'])) > ($options['expires'] * 60))
      file_put_contents($options['path'], @file_get_contents($feed, false, $timeout_context));
  }
}

/**
 * Downloads and resizes images when their cache is expired
 *
 * Possible options:
 *    path    - Path to file that is used to cache the data
 *    expires - Time in minutes that the cache expires
 *    timeout - Time to wait for a response when requesting a resource
 *    width   - Width to resize image
 *    height  - Height to resize image
 *
 * @param   array   $searches   An associative array where the key is the path to an image, and the value is an array of options
 * @return  null
 */
function loadImages(array $images)
{
  foreach ($images as $image => $options)
  {
    $timeout_context = stream_context_create(array(
      'http' => array('timeout' => $options['timeout'])
    ));
    
    if (!file_exists($options['path']) || (time() - @filemtime($options['path'])) > ($options['expires'] * 60))
      file_put_contents($options['path'], @file_get_contents($image, false, $timeout_context));

    $image = resize_image($options['path'], $options['width'], $options['height']); // Function preserves ratio; image may not be exactly the dimensions given
    imagejpeg($image, $options['path'], 100);
  }
}

/**
 * Downloads tweets from Twitter when cache is expired
 *
 * Possible options:
 *    path        - Path to file that is used to cache the data
 *    expires     - Time in minutes that the cache expires
 *    max_results - Maximum number of tweets
 *
 * @param   array   $searches   An associative array where the key is the search term, and the value is an array of options
 * @return  array
 */
function getTwitterTweets(array $searches)
{
  $twitter = new Zend_Service_Twitter_Search('json');
  $tweets = array();

  foreach ($searches as $search => $options)
  {
    if (!file_exists($options['path']) || (time() - @filemtime($options['path'])) > ($options['expires'] * 60))
    {
      $results = $twitter->search($options['search'], array('lang' => 'en', 'rpp' => $options['max_results']));
      file_put_contents($options['path'], serialize($results));
    }
    
    $tweets[$search] = @unserialize(file_get_contents($options['path']));
  }
  
  return $tweets;
}