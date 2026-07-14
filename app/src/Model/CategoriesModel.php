<?php

namespace Blogy\Model;

\defined('_PREVENT') or die;

use Blogy\Helper\DatabaseHelper;

class CategoriesModel extends ListModel
{
    public function populateState(): void
    {
        parent::populateState();
    }
    public function getItems(): array
    {
        $pdo = DatabaseHelper::getDb();

        try {
            $sql = "SELECT 
                    c.`id` AS `category_id`, 
                    c.`title` AS `category_title`, 
                    c.`slug` AS `category_slug`,
                    a.`title` AS `article_title`, 
                    a.`slug` AS `article_slug`, 
                    a.`introtext`, 
                    a.`image` 
                FROM `categories` c
                JOIN `article_category` ac ON c.`id` = ac.`category_id`
                JOIN `articles` a ON ac.`article_id` = a.`id`
                JOIN (
                    SELECT ac2.`article_id`, ac2.`category_id`,
                           ROW_NUMBER() OVER (PARTITION BY ac2.`category_id` ORDER BY a2.`created` DESC) AS `row_num`
                    FROM `article_category` ac2
                    JOIN `articles` a2 ON ac2.`article_id` = a2.`id`
                ) ranked_ac ON ac.`article_id` = ranked_ac.`article_id` AND ac.`category_id` = ranked_ac.`category_id`
                WHERE ranked_ac.`row_num` <= 3
                ORDER BY c.`id` ASC, a.`created` DESC";

            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $result = [];
            foreach ($rows as $row) {
                $catId = $row['category_id'];

                if (!isset($result[$catId])) {
                    $result[$catId] = [
                        'id' => $catId,
                        'title' => $row['category_title'],
                        'slug' => $row['category_slug'],
                        'articles' => []
                    ];
                }

                $result[$catId]['articles'][] = [
                    'title' => $row['article_title'],
                    'slug' => $row['article_slug'],
                    'introtext' => $row['introtext'],
                    'image' => $row['image']
                ];
            }

            return array_values($result);
        } catch (\PDOException $e) {
            error_log("Error retrieving data on the categories page: " . $e->getMessage());
            return [];
        }
    }
}
