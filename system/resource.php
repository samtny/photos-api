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
  
  $folder_id = !empty($_GET['folder_id']) ? $_GET['folder_id'] : NULL;
  //$derivative = !empty($_GET['derivative']) ? $_GET['derivative'] : RESOURCE_DERIVATIVE_DEFAULT;
  $search = !empty($_GET['search']) ? $_GET['search'] : NULL;

  $start = !empty($_GET['start']) ? $_GET['start'] : 0;
  $count = !empty($_GET['count']) ? $_GET['count'] : RESOURCE_PAGE;
  $sort = !empty($_GET['sort']) ? $_GET['sort'] : RESOURCE_SORT;

  $m = new MongoClient(MONGO_URI);
  $db = $m->{MONGO_DB};
  $collection = new MongoCollection($db, 'resource');

  $resources_query = array();  
  
  !empty($folder_id) && $resources_query['folder_id'] = new MongoId($folder_id);

  if (!empty($search)) {
    $exploded = explode(' ', $search);
    $cleaned = array();

    foreach ($exploded as $term) {
      $term_trimmed = trim($term);

      if (strlen($term_trimmed)) {
        $cleaned[] = $term_trimmed;
      }
    }

    if (!empty($cleaned)) {
      $search_cleaned = implode('|', $cleaned);

      $resources_query['tags'] = new MongoRegex("/$search_cleaned/i");
    }
  }

  //$resources_query['derivative'] = $derivative;

  $resources_fields = array('filename', 'key', 'derivative', 'name');

  $cursor = $collection->find($resources_query, $resources_fields)->sort(array($sort => 1))->skip($start)->limit($count);

  while ($object = $cursor->getNext()) {
    $resource_list[] = $object;
  }

  return $resource_list;
}

function resource_url($resource_key) {
  return cloudfront_signed_url($resource_key);
}

