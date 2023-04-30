<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if (!empty($_SESSION['login'])) {
  header('Location: ./');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  $errors = array();
  $errors['login'] = !empty($_COOKIE['login_error']);
  $errors['password'] = !empty($_COOKIE['password_error']);
  $errors['auth'] = !empty($_COOKIE['auth_error']);

  if (!empty($errors['login'])) {
    setcookie('login_error', '', 100000);
    $messages['login'] = '<p class="msg">Вы не заполнили логин</p>';
  }
  if (!empty($errors['password'])) {
    setcookie('password_error', '', 100000);
    $messages['password'] = '<p class="msg">Вы не заполнили пароль</p>';
  }
  if (!empty($errors['auth'])) {
    setcookie('auth_error', '', 100000);
    $messages['auth'] = '<p class="msg">Такого аккаунта не существует</p>';
  }

  include('loginform.php');
}
else {
  $login = $_POST['login'];
  $password = $_POST['password'];

  if (empty($login)) {
    setcookie('login_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  if (empty($password)) {
    setcookie('password_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if ($errors) {
    header('Location: login.php');
    exit();
  }

  $user = 'u52812';
  $pass = '8438991';
  $db = new PDO('mysql:host=localhost;dbname=u52812', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  $stmt = $db->prepare('SELECT user_id FROM users WHERE (login = ?) AND (password = ?) ');
  $stmt->execute([$login, md5($password)]);

  if ($stmt->rowCount() > 0) {
    $_SESSION['login'] = $_POST['login'];
    $stmt = $db->prepare("SELECT p_id FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $_SESSION['uid'] = $stmt->fetchColumn();
    header('Location: ./');
  } else {
    setcookie('auth_error', '1', time() + 24 * 60 * 60);
    header('Location: login.php');
    exit();
  }
}
