<?php

require_once 'vendor/autoload.php';

$host = 'localhost';
$db = 'task8';
$user = 'postgres';
$password = '';

$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    ];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
    echo "Connection successful\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$faker = Faker\Factory::create();

$pdo->exec("TRUNCATE TABLE orders RESTART IDENTITY CASCADE");
echo "Table orders truncated\n";

$sql = "INSERT INTO orders (customer_id, created_at, amount, status, shipping_address) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

$totalrecords = 1000000;
$batchsize = 1000;

$start = microtime(true);

$pdo->beginTransaction();

for ($i = 1; $i <= $totalrecords; $i++) {
    $stmt->execute([
            $faker->numberBetween(1, 50000),
            $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
            $faker->randomFloat(2, 10, 5000),
            $faker->randomElement(['new', 'processing', 'shipped', 'delivered', 'cancelled']),
            $faker->address
        ]);
    if (($i % $batchsize) === 0) {
        echo "Inserted $i records\n";
    }
}

$pdo->commit();

$end = microtime(true);
$time = round(($end - $start), 2);

echo "Inserted $totalrecords records in $time seconds\n";

