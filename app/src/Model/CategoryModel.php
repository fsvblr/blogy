<?php

namespace Blogy\Model;

\defined('_PREVENT') or die;

use Blogy\Helper\DatabaseHelper;

class CategoryModel extends ListModel
{
    public function populateState(): void
    {
        parent::populateState();

        $available_orderBy = ['created', 'hits'];
        $orderBy = isset($_REQUEST['orderBy']) ? $_REQUEST['orderBy'] : 'created';
        if (!in_array($orderBy, $available_orderBy)) {
            $orderBy = 'created';
        }
        $this->state->orderBy = $orderBy;

        $available_orderDir = ['DESC', 'ASC'];
        $orderDir = isset($_REQUEST['orderDir']) ? $_REQUEST['orderDir'] : 'DESC';
        if (!in_array($orderDir, $available_orderDir)) {
            $orderDir = 'DESC';
        }
        $this->state->orderDir = $orderDir;
    }

    public function getItems(): array
    {
        if (empty($this->state->slug)) {
            return [];
        }

        $pdo = DatabaseHelper::getDb();

        try {
            $sqlCategory = "SELECT c.`id`, c.`title`, c.`description`,
                        (SELECT COUNT(*) FROM `article_category` ac WHERE ac.`category_id` = c.`id`) as `total`
                        FROM `categories` c
                        WHERE c.`slug` = :slug 
                        LIMIT 1";
            $stmtCat = $pdo->prepare($sqlCategory);
            $stmtCat->execute(['slug' => $this->state->slug]);
            $category = $stmtCat->fetch(\PDO::FETCH_ASSOC);

            if (!$category) {
                return [];
            }

            $sqlArticles = "SELECT a.`id`, a.`title`, a.`slug`, a.`introtext`, a.`image`, a.`hits`
                        FROM `articles` a
                        JOIN `article_category` ac ON a.`id` = ac.`article_id`
                        WHERE ac.`category_id` = :category_id
                        ORDER BY {$this->state->orderBy} {$this->state->orderDir}
                        LIMIT :limit OFFSET :offset";
            $stmtArt = $pdo->prepare($sqlArticles);
            $stmtArt->bindValue(':category_id', (int) $category['id'], \PDO::PARAM_INT);
            $stmtArt->bindValue(':limit', (int) $this->state->limit, \PDO::PARAM_INT);
            $stmtArt->bindValue(':offset', (int) $this->state->offset, \PDO::PARAM_INT);
            $stmtArt->execute();
            $category['articles'] = $stmtArt->fetchAll(\PDO::FETCH_ASSOC);

            $category['pagination'] = [
                'totalArticles' => (int) $category['total'],
                'limitPerPage' => $this->state->limit,
                'totalPages' => ceil((int) $category['total'] / (int) $this->state->limit)
            ];

            return $category;

        } catch (\PDOException $e) {
            error_log("Error on category page: " . $e->getMessage());
            return [];
        }
    }
}
