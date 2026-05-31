<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Support\Database;
use PDO;

final class SlideRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::connection();
    }

    public function all(): array
    {
        $sql = 'SELECT s.id, s.category_id, s.badge_text, s.title, s.description, s.button_text, s.image, s.sort_order, s.status,
                       s.created_at, s.updated_at, c.title AS category_title
                FROM slides s
                INNER JOIN categories c ON c.id = s.category_id
                ORDER BY c.title ASC, s.sort_order ASC, s.id ASC';

        return $this->connection->query($sql)->fetchAll();
    }

    public function recent(int $limit = 5): array
    {
        $statement = $this->connection->prepare(
            'SELECT s.id, s.title, s.badge_text, s.status, c.title AS category_title
             FROM slides s
             INNER JOIN categories c ON c.id = s.category_id
             ORDER BY s.created_at DESC
             LIMIT :limit'
        );
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function byCategory(int $categoryId, bool $activeOnly = false): array
    {
        $sql = 'SELECT id, category_id, badge_text, title, description, button_text, image, sort_order, status, created_at, updated_at
                FROM slides
                WHERE category_id = :category_id';

        if ($activeOnly) {
            $sql .= ' AND status = 1';
        }

        $sql .= ' ORDER BY sort_order ASC, id ASC';

        $statement = $this->connection->prepare($sql);
        $statement->execute(['category_id' => $categoryId]);

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT id, category_id, badge_text, title, description, button_text, image, sort_order, status, created_at, updated_at
             FROM slides
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO slides (category_id, badge_text, title, description, button_text, image, sort_order, status)
             VALUES (:category_id, :badge_text, :title, :description, :button_text, :image, :sort_order, :status)'
        );
        $statement->execute([
            'category_id' => $data['category_id'],
            'badge_text' => $data['badge_text'],
            'title' => $data['title'],
            'description' => $data['description'],
            'button_text' => $data['button_text'],
            'image' => $data['image'],
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $statement = $this->connection->prepare(
            'UPDATE slides
             SET category_id = :category_id,
                 badge_text = :badge_text,
                 title = :title,
                 description = :description,
                 button_text = :button_text,
                 image = :image,
                 sort_order = :sort_order,
                 status = :status,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $statement->execute([
            'id' => $id,
            'category_id' => $data['category_id'],
            'badge_text' => $data['badge_text'],
            'title' => $data['title'],
            'description' => $data['description'],
            'button_text' => $data['button_text'],
            'image' => $data['image'],
            'sort_order' => $data['sort_order'],
            'status' => $data['status'],
        ]);
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM slides WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function setStatus(int $id, int $status): void
    {
        $statement = $this->connection->prepare(
            'UPDATE slides SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id'
        );
        $statement->execute([
            'id' => $id,
            'status' => $status,
        ]);
    }

    public function nextSortOrder(int $categoryId): int
    {
        $statement = $this->connection->prepare(
            'SELECT COALESCE(MAX(sort_order), 0) + 1 FROM slides WHERE category_id = :category_id'
        );
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    public function totalCount(): int
    {
        return (int) $this->connection->query('SELECT COUNT(*) FROM slides')->fetchColumn();
    }
}
