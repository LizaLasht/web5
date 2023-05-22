<!DOCTYPE html>
<html lang="ru">
<head>
  <title>Задание 5</title>
  <link  href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
  <?php
    if (!empty($messages)) {
      print('<div id="messages">');
      foreach ($messages as $message) {
        print($message);
      }
      print('</div>');
    }
  ?>
  <h2>Форма</h2>
  <form action="" method="POST">
    <label>
      Имя:<br/>
      <input name="name" placeholder="Введите имя" <?php if ($errors['name']) {print 'class="error"';} ?> value="<?php print $values['name']; ?>">
    </label>
    <br/>
    <label>
      email:<br/>
    <input name="email" placeholder="Введите email" <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>">
    </label>
    <br/>
      Год рождения:<br/>
      <label <?php if ($errors['year']) {print 'class="error"';} ?>>
        <select id="year" name="year">
        <?php 
          for ($i = 1922; $i <= 2022; $i++) {
            if ($i == $values['year']) {
              printf('<option selected value="%d">%d год</option>', $i, $i);
            } else {
              printf('<option value="%d">%d год</option>', $i, $i);
            }
          }
        ?>
        </select>
      </label><br/>
      <label <?php if ($errors['gender']) {print 'class="error"';} ?>>Пол:</label><br/>
      <label>
        <input type="radio" name="gender" value='w' <?php if($values['gender'] == "w") {print 'checked';}?>>Женщина
      </label> 
      <label>
        <input type="radio" name="gender" value='m' <?php if($values['gender'] == "m") {print 'checked';}?>>Мужчина  
      </label><br/>
      <label <?php if ($errors['limbs']) {print 'class="error"';} ?>>Количество конечностей:</label><br/>
      <label>
        <input type="radio" name="limbs" value="2" <?php if($values['limbs'] == "2") {print 'checked';}?>>2
      </label> 
      <label>
        <input type="radio" name="limbs" value="3" <?php if($values['limbs'] == "3") {print 'checked';}?>>3
      </label> 
      <label>
        <input type="radio" name="limbs" value="4" <?php if($values['limbs'] == "4") {print 'checked';}?>>4
      </label><br/>
      <label>
        <label <?php if ($errors['abilities']) {print 'class="error"';} ?>>Сверхспособности:</label><br/>
        <select name="abilities[]" multiple="multiple">
          <option value="1" <?php if(in_array("1", $values['abilities'])) {print('selected="selected"');} ?>>Бессмертие</option>
          <option value="2" <?php if(in_array("2", $values['abilities'])) {print('selected="selected"');} ?>>Прохождение сквозь стены</option>
          <option value="3" <?php if(in_array("3", $values['abilities'])) {print('selected="selected"');} ?>>Левитация</option>
        </select>
      </label><br/>
      <label>  
        Биография:<br/>
      <textarea name="bio" placeholder="Введите текст" <?php if ($errors['bio']) {print 'class="error"';} ?>><?php echo htmlspecialchars($values['bio']); ?></textarea>
      </label><br>
      <label <?php if ($errors['go']) {print 'class="error"';} ?>>
        <input type="checkbox" name="go"  value = "1" <?php if ($values['go'] == '1') {print 'checked';} ?>> C контрактом ознакомлен(a)
      </label><br/>
      <input type="submit" value="Отправить">
      <?php if (!empty($_SESSION['login'])) {echo '<input type="hidden" name="token" value="' . $_SESSION["token"] . '">'; } ?>
  </form>
</body>
</html>
