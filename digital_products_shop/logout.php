<?php
require_once 'core/dbConfig.php';

$_SESSION = [];
session_destroy();

header("Location: login.php?loggedout=1");
exit;
