<?php

include("assets/php/funkcije.php");

$user = User::getUserById(63);

$user->setPhone("171717171");

echo $user->updateProfile();
