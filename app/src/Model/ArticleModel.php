<?php

namespace Blogy\Model;

\defined('_PREVENT') or die;

use Blogy\Helper\DatabaseHelper;

class ArticleModel extends ItemModel
{
    public function populateState(): void
    {
        // ToDo ...
    }

    public function getItem(): array
    {
        if (empty($this->state->slug)) {
            return [];
        }

        $pdo = DatabaseHelper::getDb();

        try {
            $sqlArticle = "SELECT * FROM `articles` WHERE `slug` = :slug LIMIT 1";
            $stmtArt = $pdo->prepare($sqlArticle);
            $stmtArt->execute(['slug' => $this->state->slug]);
            $article = $stmtArt->fetch(\PDO::FETCH_ASSOC);

            if (!$article) {
                return [];
            }

            $sqlCategories = "SELECT c.`slug`, c.`title` 
                      FROM `categories` c
                      JOIN `article_category` ac ON c.`id` = ac.`category_id`
                      WHERE ac.`article_id` = :article_id";
            $stmtCat = $pdo->prepare($sqlCategories);
            $stmtCat->execute(['article_id' => $article['id']]);

            $categories = $stmtCat->fetchAll(\PDO::FETCH_ASSOC);

            $article['categories'] = $categories;

            $article['related'] = $this->getRelatedArticles($pdo, $article['id']);

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }

        return $article;
    }

    /**
     * Retrieves a list of similar articles based on shared categories.
     */
    public static function getRelatedArticles(\PDO $pdo, int $currentArticleId, int $limit = 3): array
    {
        try {
            $sql = "SELECT a.title, a.slug, a.introtext, a.image, COUNT(ac2.`category_id`) as `common_categories`
                FROM `article_category` ac1
                -- 1. Find other articles that have the same categories.
                JOIN `article_category` ac2 ON ac1.`category_id` = ac2.`category_id` AND ac2.`article_id` != ac1.`article_id`
                -- 2. Pulling in the data from these articles.
                JOIN `articles` a ON ac2.`article_id` = a.`id`
                -- 3. Filter by ID of the current article
                WHERE ac1.`article_id` = :current_id
                -- 4. Group the results by category to count the matches.
                GROUP BY a.`id`
                -- 5. Sorting: first by the number of shared categories, then by recency.
                ORDER BY `common_categories` DESC, a.`created` DESC
                LIMIT :limit";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':current_id', $currentArticleId, \PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            error_log("Error retrieving similar articles: " . $e->getMessage());
            return [];
        }
    }

    public function addHit(): bool
    {
        if (empty($this->state->slug)) {
            return false;
        }

        $pdo = DatabaseHelper::getDb();

        try {
            $sql = "UPDATE `articles` SET `hits` = `hits` + 1 WHERE `slug` = :slug LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['slug' => $this->state->slug]);

        } catch (\PDOException $e) {
            error_log("Error updating article views SLUG {$this->state->slug}: " . $e->getMessage());
            return false;
        }

        return true;
    }
}
