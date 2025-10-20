<?php

use DG\BypassFinals;
use Symfony\Component\Dotenv\Dotenv;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

require dirname(__DIR__).'/vendor/autoload.php';
//Le package dg/bypass-finals
// permet de “désactiver” le mot-clé final sur les classes PHP pendant les tests.
BypassFinals::enable();

(new Dotenv(false))->loadEnv(dirname(__DIR__).'/.env.test');

if (file_exists(dirname(__DIR__).'/.env.test.local')) {
    (new Dotenv(true))->loadEnv(dirname(__DIR__).'/.env.test.local');
}

$isUnitTesting = require 'isUnitTesting.php';

if ($isUnitTesting === false) {
    require 'databaseInitializing.php';
}
