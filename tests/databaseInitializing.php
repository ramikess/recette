<?php

use MongoDB\Client as MongoClient;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

function colorize($text, $colorCode): string
{
    return "\033[{$colorCode}m{$text}\033[0m";
}

// 🔹 Vérification : on doit être sur une base de test
if (empty($_ENV['MONGODB_URL'])) {
    throw new InvalidArgumentException('Missing MONGODB_URL environment variable');
}

// Extrait le nom de la base Mongo (ex: "app_test" dans mongodb://user:pass@host/dbname)
preg_match('~/([^/?]+)(?:\?|$)~', $_ENV['MONGODB_URL'], $matches);
$databaseName = $matches[1] ?? null;

if (!$databaseName) {
    throw new InvalidArgumentException('Unable to determine MongoDB database name from MONGODB_URL');
}

if (stripos($databaseName, 'test') === false) {
    throw new InvalidArgumentException('The MongoDB database does not appear to be a test database.');
}

echo PHP_EOL . colorize("Using MongoDB database: {$databaseName}", 36) . PHP_EOL;

// 🔹 Connexion au serveur MongoDB
try {
    $client = new MongoClient($_ENV['MONGODB_URL']);
    $db = $client->selectDatabase($databaseName);
} catch (Throwable $e) {
    echo colorize('❌ Unable to connect to MongoDB: ' . $e->getMessage(), 31) . PHP_EOL;
    exit(1);
}

// 🔹 Vérifie si la base existe (Mongo crée la DB à la première insertion)
$databases = $client->listDatabases();
$exists = false;
foreach ($databases as $database) {
    if ($database->getName() === $databaseName) {
        $exists = true;
        break;
    }
}

if ($exists) {
    echo colorize("ℹ️ The MongoDB test database '{$databaseName}' already exists.", 34) . PHP_EOL;
} else {
    echo colorize("✅ Creating MongoDB test database '{$databaseName}'...", 32) . PHP_EOL;
    // MongoDB crée la base automatiquement dès qu'on insère quelque chose
    $db->createCollection('init_tmp');
    $db->dropCollection('init_tmp');
    echo colorize("✅ Database '{$databaseName}' created successfully.", 32) . PHP_EOL;
}

// 🔹 Mise à jour du schéma (Doctrine MongoDB ODM)
echo PHP_EOL . colorize('Updating MongoDB schema via Doctrine ODM...', 36) . PHP_EOL;

$process = new Process(explode(' ', 'php bin/console doctrine:mongodb:schema:update'));
$process->run();

if (!$process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

$output = $process->getOutput() ?: $process->getErrorOutput();
if (stripos($output, 'Nothing to update') !== false) {
    echo colorize('ℹ️ The MongoDB schema is already up to date.', 34) . PHP_EOL;
} elseif (stripos($output, 'Updating document indexes') !== false) {
    echo colorize('✅ The MongoDB schema has been successfully updated.', 32) . PHP_EOL;
} else {
    echo colorize('⚠️ Unexpected output from schema update:', 33) . PHP_EOL;
    echo $output . PHP_EOL;
}

echo PHP_EOL . colorize('MongoDB test database initialization complete ✅', 32) . PHP_EOL;
