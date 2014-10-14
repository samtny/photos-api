<?php

require_once 'config.php';

function folder_get($folder_id) {
  $folder = NULL;

  $m = new MongoClient(MONGO_URI);
  $db = $m->{MONGO_DB};
  $collection = new MongoCollection($db, 'folder');

  $folders_query = array('_id' => new MongoId($folder_id));
  $folders_fields = array('filename', 'key');

  $folder = $collection->findOne($folders_query, $folders_fields);

  return $folder;
}

function folder_list($parent = "", $start = 0, $count = FOLDER_PAGE, $sort = FOLDER_SORT) {
  $folder_list = array();

  $m = new MongoClient(MONGO_URI);
  $db = $m->{MONGO_DB};
  $collection = new MongoCollection($db, 'folder');
  
  $folders_query = array('parent' => $parent);
  $folders_fields = array('name', 'parent', 'children');

  $cursor = $collection->find($folders_query, $folders_fields)->sort(array($sort => 1))->skip($start)->limit($count);

  while ($object = $cursor->getNext()) {
    $folder_list[] = $object;
  }

  return $folder_list;
}

