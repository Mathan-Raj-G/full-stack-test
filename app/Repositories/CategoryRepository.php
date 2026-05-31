<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Support\Database;
use PDO;

final class CategoryRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connection();
    }

    public function all(bool $activeOnly = false): array
    {
        $sql = 'SELECT id, title, icon, status, created_at, updated_at
                FROM categories';

        if ($activeOnly) {
            $sql .= ' WHERE status = 1';
        }

        $sql .= ' ORDER BY created_at ASC, id ASC';

        return $this->connection->query($sql)->fetchAll();
    }

    public function allWithSlideCount(): array
    {
        $sql = 'SELECT c.id, c.title, c.icon, c.status, c.created_at, c.updated_at, COUNT(s.id) AS slide_count
                FROM categories c
                LEFT JOIN slides s ON s.category_id = c.id
                GROUP BY c.id
                ORDER BY c.created_at ASC, c.id ASC';

        return $this->connection->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT id, title, icon, status, created_at, updated_at FROM categories WHERE id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO categories (title, icon, status) VALUES (:title, :icon, :status)'
        );
        $statement->execute([
            'title' => $data['title'],
            'icon' => $data['icon'],
            'status' => $data['status'],
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $statement = $this->connection->prepare(
            'UPDATE categories
             SET title = :title, icon = :icon, status = :status, updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $id,
            'title' => $data['title'],
            'icon' => $data['icon'],
            'status' => $data['status'],
        ]);
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM categories WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function setStatus(int $id, int $status): void
    {
        $statement = $this->connection->prepare(
            'UPDATE categories SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id'
        );
        $statement->execute([
            'id' => $id,
            'status' => $status,
        ]);
    }

    public function totalCount(): int
    {
        return (int) $this->connection->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    }
}
