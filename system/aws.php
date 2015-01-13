<?php

require_once (__DIR__ . '/../vendor/autoload.php');

use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;

if (defined('AWS_ACCESS_KEY_ID')) {
  $params = array(
    'key' => AWS_ACCESS_KEY_ID,
    'secret' => AWS_SECRET_ACCESS_KEY,
    'region' => EC2_REGION,
  );
}
else {
  $cacheAdapter = new DoctrineCacheAdapter(new FilesystemCache('/tmp/cache'));

  $params = array(
    'credentials.cache' => $cacheAdapter,
    'profile' => EC2_IAM_ROLE,
    'region' => EC2_REGION,
  );
}

return array(
  'includes' => array('_aws'),
  'services' => array(
    'default_settings' => array(
      'params' => $params,
    ),
  ),
);
