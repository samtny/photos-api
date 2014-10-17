<?php

require_once 'config.php';

function folder_get($folder_id) {
  $folder = NULL;

  $m = new MongoClient(MONGO_URI);
  $db = $m->{MONGO_DB};
  $collection = new MongoCollection($db, 'folder');

  $query = array('_id' => new MongoId($folder_id));
  $fields = array('name', 'children');

  $folder = $collection->findOne($query, $fields);

  return $folder;
}

function folder_list($parent = '', $start = 0, $count = FOLDER_PAGE, $sort = FOLDER_SORT) {
  $folder_list = array();

  $m = new MongoClient(MONGO_URI);
  $db = $m->{MONGO_DB};
  $collection = new MongoCollection($db, 'folder');
  
  $query = array('name' => $parent);
  $fields = array('name', 'children');

  $cursor = $collection->find($query, $fields)->sort(array($sort => 1))->skip($start)->limit($count);
  $object = $cursor->getNext();

  foreach ($object['children'] as $child) {
    $folder = folder_get($child);
    folder_inflate($folder);

    $folder_list[] = $folder;
  }

  return $folder_list;
}

function folder_inflate(&$folder) {
  $m = new MongoClient(MONGO_URI);
  $db = $m->{MONGO_DB};
  $collection = new MongoCollection($db, 'folder');

  foreach ($folder['children'] as &$item) {
    $item = folder_get($item);

    folder_inflate($item);
  }
}

