<?php

use Core\App;

$config = require_once(__DIR__ . '/config');

$app = new App($config);

return $app;
