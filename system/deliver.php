<?php

function deliver_template($template) {
  include 'templates/' . $template . '.tpl.php';
  exit(0);
}

function deliver_redirect($url, $perm = false) {
  header('Cache-Control: public, max-age=3000');
  header('Location: ' . $url);
  exit(0);
}

function deliver_json($data) {
  header('Content-Type: application/json');
  header('Cache-Control: public, max-age=300');
  echo json_encode($data);
  exit(0);
}

function deliver_string($data) {
  header('Content-Type: text/plain');
  echo $data;
  exit (0);
}

