<?php
include('../includes/function.php');
session_unset();
session_destroy();
header('location:/perigueux_php_full/index.php');