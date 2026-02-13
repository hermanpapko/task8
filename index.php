<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Database;
use App\Seeder\OrderSeeder;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dbConfig = new Database(
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD']
);

$pdo = $dbConfig->getConnection();

if (!$pdo) {
    die("Database connection failed");
}

$faker = \Faker\Factory::create();
$seeder = new OrderSeeder($pdo, $faker);

$start = microtime(true);

$seeder->seed(1000000);

$time = round(microtime(true) - $start, 2);
echo "Inserted 1,000,000 records in $time seconds.\n";