<?php

require_once 'system/deliver.php';
require_once 'system/resource.php';
require_once 'system/folder.php';

$request_uri = $_SERVER['REQUEST_URI'];
$action_params = explode("?", trim($request_uri, '/'));

switch ($action_params[0]) {
  case "folder_list":
    $parent = !empty($_GET['parent']) ? $_GET['parent'] : '';
    $start = !empty($_GET['start']) ? $_GET['start'] : 0;
    $count = !empty($_GET['count']) ? $_GET['count'] : FOLDER_PAGE;
    $sort = !empty($_GET['sort']) ? $_GET['sort'] : FOLDER_SORT;

    $folder_list = folder_list($parent, $start, $count, $sort);
    deliver_json(folder_list());

    break;

  case "resource_list":
    $resource_list = resource_list();
    deliver_json($resource_list);

    break;

  case "resource":
    $resource_id = $_GET['resource_id'];
    $resource = resource_get($resource_id);
    $resource_key = $resource['key'];
    $resource_url = resource_url($resource_key);
    deliver_redirect($resource_url);

    break;

  default:
    deliver_template('index');

    break;
}

