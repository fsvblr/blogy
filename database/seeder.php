<?php

/**
 * Constant that is checked in included files to prevent direct access.
 */

\define('_PREVENT', 1);

\define('PATH_ROOT', dirname(__DIR__));

if (!file_exists(PATH_ROOT . '/app/configuration.php')) {
    echo 'No configuration file found. Exiting...';
    exit;
}
require_once PATH_ROOT . '/app/configuration.php';

if (!file_exists(PATH_ROOT . '/app/cashe/autoload_psr4.php')) {
    echo 'Namespaces file not found. Exiting...';
    exit;
}
$map = require_once PATH_ROOT . '/app/cashe/autoload_psr4.php';

if (!file_exists(PATH_ROOT . '/app/autoload.php')) {
    echo 'Autoload file not found. Exiting...';
    exit;
}
require_once PATH_ROOT . '/app/autoload.php';

// ------------------------------------------------------------------

$config = new \Config();

$password = isset($_GET['pass']) ? $_GET['pass'] : '';
$password_seeder = !empty($config->password_seeder) ? $config->password_seeder : null;
if (empty($password_seeder) || $password_seeder !== $password) {
    exit('Invalid password.');
}

$seeder_count_categories = (int) $config->seeder_count_categories;
$seeder_count_articles = (int) $config->seeder_count_articles;

$intro = "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.";
$full = "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.";

use Blogy\Helper\DatabaseHelper;

$pdo = DatabaseHelper::getDb();

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE `article_category`");
    $pdo->exec("TRUNCATE TABLE `categories`");
    $pdo->exec("TRUNCATE TABLE `articles`");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $sqlCategory = "INSERT INTO `categories` (`id`, `title`, `slug`, `description`, `created`) 
                    VALUES (:id, :title, :slug, :description, NOW() + INTERVAL :offset SECOND)";
    $stmtCat = $pdo->prepare($sqlCategory);

    for ($i = 1; $i <= $seeder_count_categories; $i++) {
        $stmtCat->execute([
            'id'          => $i,
            'title'       => 'Category ' . $i,
            'slug'        => 'category-' . $i,
            'description' => $intro,
            'offset'      => $i
        ]);
    }

    $sqlArticle = "INSERT INTO `articles` (`id`, `title`, `slug`, `introtext`, `fulltext`, `image`, `hits`, `created`) 
                   VALUES (:id, :title, :slug, :introtext, :fulltext, :image, 0, NOW() + INTERVAL :offset SECOND)";
    $stmtArt = $pdo->prepare($sqlArticle);

    $sqlRelation = "INSERT INTO `article_category` (`article_id`, `category_id`) VALUES (:article_id, :category_id)";
    $stmtRel = $pdo->prepare($sqlRelation);

    for ($i = 1; $i <= $seeder_count_articles; $i++) {
        $image = 'image0' . mt_rand(1, 3) . '.webp';

        $stmtArt->execute([
            'id'        => $i,
            'title'     => 'Article ' . $i,
            'slug'      => 'article-' . $i,
            'introtext' => $intro,
            'fulltext'  => $full,
            'image'     => $image,
            'offset'    => $i
        ]);

        $category_id_1 = mt_rand(1, $seeder_count_categories);
        $category_id_2 = mt_rand(1, $seeder_count_categories);

        $stmtRel->execute([
            'article_id'  => $i,
            'category_id' => $category_id_1
        ]);

        if ($category_id_1 !== $category_id_2) {
            $stmtRel->execute([
                'article_id'  => $i,
                'category_id' => $category_id_2
            ]);
        }
    }

    echo 'Successfully added:<br>- categories - ' . $seeder_count_categories . ',<br>- articles: ' . $seeder_count_articles . '<br><br>';

} catch (\PDOException $e) {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    exit($e->getMessage());
}

exit('Done');
