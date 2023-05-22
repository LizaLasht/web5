<?php

header('Content-Type: text/html; charset=UTF-8');

$user = 'u52812'; 
$pass = '8438991';
$db = new PDO('mysql:host=localhost;dbname=u52812', $user, $pass, [PDO::ATTR_PERSISTENT => true]); 

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
    if (!empty($_COOKIE['password'])) {
      $messages[] = sprintf(' Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['password']));
    }
    setcookie('login', '', 100000);
    setcookie('password', '', 100000);
  }

  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['go'] = !empty($_COOKIE['go_error']);

  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">Заполните name.</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните email.</div>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Заполните year.</div>';
  }
  if ($errors['gender']) {
    setcookie('gender_error', '', 100000);
    $messages[] = '<div class="error">Заполните gender.</div>';
  }
  if ($errors['limbs']) {
    setcookie('limbs_error', '', 100000);
    $messages[] = '<div class="error">Заполните limbs.</div>';
  }
  if ($errors['abilities']) {
    setcookie('abilities_error', '', 100000);
    $messages[] = '<div class="error">Заполните abilities.</div>';
  }  
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">Заполните bio.</div>';
  }
  if ($errors['go']) {
    setcookie('go_error', '', 100000);
    $messages[] = '<div class="error">Заполните go.</div>';
  }

  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : htmlspecialchars(strip_tags($_COOKIE['name_value']));
  $values['email'] = empty($_COOKIE['email_value']) ? '' : htmlspecialchars(strip_tags($_COOKIE['email_value']));
  $values['year'] = empty($_COOKIE['year_value']) ? '' : htmlspecialchars(strip_tags($_COOKIE['year_value']));
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : htmlspecialchars(strip_tags($_COOKIE['gender_value']));
  $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : htmlspecialchars(strip_tags($_COOKIE['limbs_value']));
  $values['abilities'] = empty($_COOKIE['abilities_value']) ? [] : json_decode($_COOKIE['abilities_value']);
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : htmlspecialchars(strip_tags($_COOKIE['bio_value']));
  $values['go'] = empty($_COOKIE['go_value']) ? '' : htmlspecialchars(strip_tags($_COOKIE['go_value']));

  if (count(array_filter($errors)) === 0 && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
    echo $_SESSION['token'];
    $login = $_SESSION['login'];
    try {
      $stmt = $db->prepare("SELECT p_id FROM users WHERE login = ?");
      $stmt->execute([$login]);
      $p_id = $stmt->fetchColumn();

      $stmt = $db->prepare("SELECT name, email, year, gender, limbs, bio, go FROM person WHERE p_id = ?");
      $stmt->execute([$p_id]);
      $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $stmt = $db->prepare("SELECT sup_id FROM person_superpower WHERE p_id = ?");
      $stmt->execute([$p_id]);
      $abil = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

      if (!empty($dates[0]['name'])) {
        $values['name'] = htmlspecialchars(strip_tags($dates[0]['name']));
      }
      if (!empty($dates[0]['email'])) {
        $values['email'] = htmlspecialchars(strip_tags($dates[0]['email']));
      }
      if (!empty($dates[0]['year'])) {
        $values['year'] = htmlspecialchars(strip_tags($dates[0]['year']));
      }
      if (!empty($dates[0]['gender'])) {
        $values['gender'] = htmlspecialchars(strip_tags($dates[0]['gender']));
      }
      if (!empty($dates[0]['limbs'])) {
        $values['limbs'] = htmlspecialchars(strip_tags($dates[0]['limbs']));
      }
      if (!empty($dates[0]['bio'])) {
        $values['bio'] = htmlspecialchars(strip_tags($dates[0]['bio']));
      } 
      if (!empty($dates[0]['go'])) {
        $values['go'] = htmlspecialchars(strip_tags($dates[0]['go']));
      } 
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }
    printf('<header><p>Вход с логином %s; uid: %d</p><a href=logout.php>Выйти</a></header>', $_SESSION['login'], $_SESSION['uid']);
  }
  include('form.php');
}
else {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $year = $_POST['year'];
  $gender = $_POST['gender'];
  $limbs = $_POST['limbs'];
  $bio = $_POST['bio'];
  $go = $_POST['go'];
  $errors = FALSE;
  if (empty($name)) {
    $errors = TRUE;
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    setcookie('name_value', $name, time() + 30 * 24 * 60 * 60);
  } else {
    setcookie('name_value', $name, time() + 30 * 24 * 60 * 60);
  }
  if (empty($email) || !preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $email)) {
    $errors = TRUE;
    setcookie('email_value', $email, time() + 30 * 24 * 60 * 60);
    setcookie('email_error', '1', time() + 24 * 60 * 60);
  } else {
    setcookie('email_value', $email, time() + 30 * 24 * 60 * 60);
  }
  if (empty($year) || !is_numeric($year) || (int)$year <= 1922 || (int)$year >= 2022) {
    $errors = TRUE;
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    setcookie('year_value', $year, time() + 30 * 24 * 60 * 60);
  } else {
    setcookie('year_value', $year, time() + 30 * 24 * 60 * 60);
  }
  if ($gender !== 'm' && $gender !== 'w'){
    $errors = TRUE;
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
  } else {
    setcookie('gender_value', $gender, time() + 30 * 24 * 60 * 60);
  }
  if ($limbs !== '2' && $limbs !== '3' && $limbs !== '4') {  
    $errors = TRUE;
    setcookie('limbs_error', '1', time() + 24 * 60 * 60);
  } else {
    setcookie('limbs_value', $limbs, time() + 30 * 24 * 60 * 60);
  }
  if (empty($_POST['abilities']) || !is_array($_POST['abilities'])) {
    $errors = TRUE;
    setcookie('abilities_error', '1', time() + 24 * 60 * 60);
  } else {
    setcookie('abilities_value', json_encode($_POST['abilities']), time() + 30 * 24 * 60 * 60);
  }
  if (empty($bio) || strlen($bio) > 128) {
    $errors = TRUE;
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    setcookie('bio_value', $bio, time() + 30 * 24 * 60 * 60);
  } else{
    setcookie('bio_value', $bio, time() + 30 * 24 * 60 * 60);
  }
  if ($go == '') {
    $errors = TRUE;
    setcookie('go_error', '1', time() + 24 * 60 * 60);
  } else {
    setcookie('go_value', $go, time() + 30 * 24 * 60 * 60);
  }

  if ($errors) {
    header('Location: index.php');
    exit();
  }
  else {
    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('abilities_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('go_error', '', 100000);
  }

  if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    if (!empty($_POST['token']) && hash_equals($_POST['token'], $_SESSION['token'])) {
      $login = $_SESSION['login'];
      try {
        $stmt = $db->prepare("SELECT p_id FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $p_id = $stmt->fetchColumn();

        $stmt = $db->prepare("UPDATE person SET name = ?, email = ?, year = ?, gender = ?, limbs = ?, bio = ?, go = ?
          WHERE p_id = ?");
        $stmt->execute([$name, $email, $year, $gender, $limbs, $bio, $go, $p_id]);

        $stmt = $db->prepare("SELECT sup_id FROM person_superpower WHERE p_id = ?");
        $stmt->execute([$p_id]);
        $abil = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        if (array_diff($abil, $_POST['abilities'])) {
          $stmt = $db->prepare("DELETE FROM person_superpower WHERE p_id = ?");
          $stmt->execute([$p_id]);

          $stmt = $db->prepare("INSERT INTO person_superpower (p_id, sup_id) VALUES (?, ?)");
          foreach ($_POST['abilities'] as $sup_id) {
            $stmt->execute([$p_id, $sup_id]);
          }
        }
      } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
      }
    } else {
      die('Ошибка CSRF: недопустимый токен');
    }
  }
  else {
    $login = 'user' . rand(1, 1000);
    $password = rand(1, 100);
    setcookie('login', $login);
    setcookie('password', $password);
    try {
      $stmt = $db->prepare("INSERT INTO person (name, email, year, gender, limbs, bio, go) VALUES (?, ?, ?, ?, ?, ?, ?);");
      $stmt->execute([$name, $email, $year, $gender, $limbs, $bio, $go]);
      $p_id = $db->lastInsertId();
      if (isset($_POST['abilities'])) {
        $stmt = $db -> prepare("INSERT INTO person_superpower (p_id, sup_id) VALUES (?, ?);");
        foreach ($_POST['abilities'] as $superpower) {
          $stmt->execute([$p_id, $superpower]);
        }
      }
      $stmt = $db->prepare("INSERT INTO users (p_id, login, password) VALUES (?, ?, ?)");
      $stmt->execute([$p_id, $login, md5($password)]);
    } catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  }

  setcookie('save', '1');
  header('Location: ./');
}
