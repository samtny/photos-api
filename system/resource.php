<?php

require_once 'config.php';
require_once 'cloudfront.php';

function resource_get($resource_id) {
  $resource = NULL;

  $m = new MongoClient(MONGO_URI);
  $db = $m->{MONGO_DB};
  $collection = new MongoCollection($db, 'resource');

  $resources_query = array('_id' => new MongoId($resource_id));
  $resources_fields = array('filename', 'key');

  $resource = $collection->findOne($resources_query, $resources_fields);

  return $resource;
}

function resource_list($start = 0, $count = RESOURCE_PAGE, $sort = RESOURCE_SORT) {
  $resource_list = array();

  $m = new MongoClient(MONGO_URI);
  $db = $m->{MONGO_DB};
  $collection = new MongoCollection($db, 'resource');
  
  $resources_query = array();
  $resources_fields = array('filename', 'key');

  $cursor = $collection->find($resources_query, $resources_fields)->sort(array($sort => 1))->skip($start)->limit($count);

  while ($object = $cursor->getNext()) {
    $resource_list[] = $object;
  }

  return $resource_list;
}

function resource_url($resource_key) {
  return cloudfront_signed_url($resource_key);
}

