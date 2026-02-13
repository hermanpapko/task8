<?php
declare(strict_types=1);

namespace App\Database\Seeds;

use PDO;
use Faker\Generator;

class OrderSeeder
{
    private const BATCH_SIZE = 1000;

    public function __construct(
        private PDO $pdo,
        private Generator $faker
    ) {}

    public function seed(int $totalRecords): void
    {
        $this->truncateTable();

        $sql = "INSERT INTO orders (customer_id, created_at, amount, status, shipping_address) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $this->pdo->beginTransaction();

        for ($i = 1; $i <= $totalRecords; $i++) {
            $stmt->execute([
                $this->faker->numberBetween(1, 50000),
                $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
                $this->faker->randomFloat(2, 10, 5000),
                $this->faker->randomElement(['new', 'processing', 'shipped', 'delivered', 'cancelled']),
                $this->faker->address
            ]);

            if ($i % self::BATCH_SIZE === 0) {
                echo "Inserted $i records..." . PHP_EOL;
            }
        }

        $this->pdo->commit();
    }

    private function truncateTable(): void
    {
        $this->pdo->exec("TRUNCATE TABLE orders RESTART IDENTITY CASCADE");
        echo "Table orders truncated." . PHP_EOL;
    }
}