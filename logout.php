<?php

session_start();
session_destroy();
setcookie('name_value', '', 100000);
setcookie('email_value', '', 100000);
setcookie('year_value', '', 100000);
setcookie('limbs_value', '', 100000);
setcookie('gender_value', '', 100000);
setcookie('abilities_value', '', 100000);
setcookie('bio_value', '', 100000);
setcookie('go_value', '', 100000);
header('Location: ./');
exit();
