<?php 
require_once './util/util.php'; 

if (isset($_SESSION['user'])) {
  header('Location: index.php?t=list&a=view&page=1');
  exit;
}

if ($_POST) {
$username = htmlspecialchars(strip_tags(trim($_POST['username'])));
$email = htmlspecialchars(strip_tags(trim($_POST['email'])));
$password = htmlspecialchars(strip_tags(trim($_POST['password'])));
$password = password_hash($password, PASSWORD_DEFAULT);

$user = [
  'username' => $username,
  'email' => $email, 
  'password' => $password
];

$content['user'][] = $user;
file_put_contents('text.txt', json_encode($content));


header('Location: index.php?t=user&a=login'); exit;

}

