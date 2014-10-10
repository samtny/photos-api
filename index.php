<?php

require_once 'system/deliver.php';
require_once 'system/resource.php';

$request_uri = $_SERVER['REQUEST_URI'];
$params = explode("/", trim($request_uri, '/'));

switch ($params[0]) {
  case "resource_list":
    $start = isset($params[1]) ? $params[1] : 0;
    $count = isset($params[2]) ? $params[2] : RESOURCE_PAGE;
    $sort = !empty($params[3]) ? $params[3] : RESOURCE_SORT;

    $resource_list = resource_list($start, $count, $sort);
    deliver_json($resource_list);

    break;

  case "resource":
    $resource_id = $params[1];
    $resource = resource_get($resource_id);
    $resource_key = $resource['key'];
    $resource_url = resource_url($resource_key);
    deliver_redirect($resource_url);

    break;

  default:
    deliver_template('index');

    break;
}

