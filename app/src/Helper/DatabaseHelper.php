<?php

namespace Blogy\Helper;

\defined('_PREVENT') or die;

abstract class DatabaseHelper
{
    /**
     * @return \PDO
     */
    public static function getDb()
    {
        $config = new \Config();

        $host = $config->host;
        $db   = $config->db;
        $user = $config->user;
        $pass = $config->password;
        $charset = $config->charset;

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        return $pdo;
    }

    public static function splitSql($sql): array
    {
        $start = 0;
        $open = false;
        $char = '';
        $end = strlen($sql);
        $queries = array();

        for ($i = 0; $i < $end; $i++) {
            $current = substr($sql, $i, 1);
            if (($current == '"' || $current == '\'')) {
                $n = 2;

                while (substr($sql, $i - $n + 1, 1) == '\\' && $n < $i) {
                    $n++;
                }

                if ($n % 2 == 0) {
                    if ($open) {
                        if ($current == $char) {
                            $open = false;
                            $char = '';
                        }
                    } else {
                        $open = true;
                        $char = $current;
                    }
                }
            }

            if (($current == ';' && !$open) || $i == $end - 1) {
                $queries[] = substr($sql, $start, ($i - $start + 1));
                $start = $i + 1;
            }
        }

        return $queries;
    }
}
