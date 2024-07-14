<?php
  define('ROOT', __DIR__);
  
  $content = file_get_contents('text.txt');
  $content = json_decode($content, true);

  $userId =isset($_SESSION['user']) ? $_SESSION['user']['id'] : '';

  $param = isset($_GET['p']) ? $_GET['p']  : null;
$id = isset($_GET['id']) ? $_GET['id'] - 1 : null;

$currentDate = new DateTime();
$currentDate = $currentDate->setTimezone(new DateTimeZone('Europe/Paris'))->format("d-m-Y H:i");

if (!isset($_SESSION['granted'])) {
  $_SESSION['granted'] = [];
}
  
  function dump(mixed $data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}



