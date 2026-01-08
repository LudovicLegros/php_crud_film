<?php
include('../includes/function.php');
session_unset();
session_destroy();
header('location:'.BASE_URL.'/index.php');