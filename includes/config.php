<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


date_default_timezone_set('UTC');


define('RECORDS_PER_PAGE', 20);
define('RECORDS_PER_LOAD', 10);


$SUPPORTED_LANGUAGES = [
    'en_US' => 'English (US)',
    'de_DE' => 'German (Germany)',
    'fr_FR' => 'French (France)'
];


require_once __DIR__ . '/../vendor/autoload.php';