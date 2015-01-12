<?php

require_once (__DIR__ . '/../vendor/autoload.php');

use Aws\Common\Aws;

function cloudfront_signed_url($resource_key) {
  $signedUrl = NULL;

  $aws = Aws::factory('system/aws.php');

  $cloudFront = $aws->get('CloudFront');

  $hostUrl = CLOUDFRONT_URL;
  //$expires = time() + 300;
  $expires = strtotime('tomorrow midnight');

  $resourceUrl = $hostUrl . '/' . urlencode($resource_key);
//deliver_string($resourceUrl);
  $policy = <<<POLICY
{
  "Statement": [
    {
      "Resource": "{$resourceUrl}",
      "Condition": {
        "IpAddress": {"AWS:SourceIp": "{$_SERVER['REMOTE_ADDR']}/32"},
        "DateLessThan": {"AWS:EpochTime": {$expires}}
      }
    }
  ]
}
POLICY;

  $signedUrl = $cloudFront->getSignedUrl(array(
    'url' => $resourceUrl,
    'policy' => $policy,
    'private_key' => CLOUDFRONT_PRIVATE_KEY,
    'key_pair_id' => CLOUDFRONT_KEY_PAIR_ID,
  ));

  return $signedUrl;
}

