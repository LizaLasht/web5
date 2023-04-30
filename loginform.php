<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    .error {
      color: red;
    }
  </style>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>
<body>
<?php 
  if (!empty($messages)) {
    foreach ($messages as $message) {
      print($message);
    }
  }
?>
</div>
<form action="" method="post">
  <h1>Вход</h1>
  <p <?php  if ($errors['login']) print 'class="error"'?>>Логин</p>
  <input name="login">
  <p <?php  if ($errors['password']) print 'class="error"'?>>Пароль</p>
  <input name="password" type="password">
  <input type="submit" value="Войти">
  </form>
</div>
</body>
