<?php

require_once 'vendor/autoload.php';

use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;

$cacheAdapter = new DoctrineCacheAdapter(new FilesystemCache('/tmp/cache'));

return array(
  'includes' => array('_aws'),
  'services' => array(
    'default_settings' => array(
      'params' => array(
        'credentials.cache' => $cacheAdapter,
        'profile' => EC2_IAM_ROLE,
        'region' => EC2_REGION,
      ),
    ),
  ),
);

