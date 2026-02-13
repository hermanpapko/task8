<?php

require_once 'vendor/autoload.php';
require_once 'Database.php';
require_once 'Seeder.php';

// 1. Настраиваем подключение
$dbConfig = new Database('localhost', 'my_db', 'user', 'pass');
$pdo = $dbConfig->getConnection();

if (!$pdo) {
    die("Database connection failed.");
}

$faker = Faker\Factory::create();
$seeder = new \App\Database\Seeds\OrderSeeder($pdo, $faker);

// 3. Запускаем процесс и замеряем время
$start = microtime(true);

$seeder->seed(1000000);

$time = round(microtime(true) - $start, 2);
echo "Done! Inserted 1,000,000 records in $time seconds.\n";