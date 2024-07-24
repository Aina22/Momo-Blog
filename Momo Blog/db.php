<?php
$DB_DSN = "mysql:host=localhost;dbname=blog";
$username = "root";
$password = "";
$DB = new PDO($DB_DSN, $username,$password);
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);