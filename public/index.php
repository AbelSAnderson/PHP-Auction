<?php

require_once(__DIR__ . '/../app/bootstrap.php');

echo "Hello World";

\App\Lib\Database::getConnection();