<?php 
require_once './util/util.php'; 

if (isset($_SESSION['user'])) {
  header('Location: index.php?t=list&a=view&page=1');
  exit;
}

if ($_POST) {
$email = htmlspecialchars(strip_tags(trim($_POST['email'])));
$password = htmlspecialchars(strip_tags(trim($_POST['password'])));

$id = array_search($email, array_column($content['user'], 'email'));

$user = $content['user'][$id];

if ($user['email'] == $email && password_verify($password, $user['password'])) {
  
  $_SESSION['user'] = [
    'id' => $id,
    'email' => $user['email'],
    'username' => $user['username']
  ];

  header('Location: index.php?t=list&a=view&page=1'); exit;

} else {
  echo '<div class="alert-danger">L\'email ou le mot de passe est incorrect</div>';
}

}

