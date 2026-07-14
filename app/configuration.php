<?php

\defined('_PREVENT') or die;

class Config {
	public $dbtype = 'mysqli';
    public $charset = 'utf8mb4';
	public $host = 'MySQL-8.0';
	public $user = 'root';
	public $password = '';
	public $db = 'blogy';

    public $limit = 5;

    public $password_migration = 'secret';
    public $password_seeder = 'secret';
    public $seeder_count_categories = 3;
    public $seeder_count_articles = 20;
}