<?php

use Core\App;

$config = require_once(__DIR__ . '/configs/app.php');

$app = new App($config);

return $app;
