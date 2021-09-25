<?php

namespace App\core\db;

use App\core\Application;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config = [])
    {
        $dsn = $config['db']['dsn'];
        $username = $config['db']['username'];
        $password = $config['db']['password'];

        $this->pdo = new \PDO($dsn, $username, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigration()
    {
        $this->createMigrationTable();

        $migrations = $this->getMigrations();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = array_diff($migrations, $appliedMigrations);

        if(!empty($newMigrations)){
            $this->executeMigrations($newMigrations);
            $this->insertMigrations($newMigrations);
        }else {
            echo "All migrations are applied.";
        }
    }

    private function createMigrationTable(){
        $statement = $this->pdo->prepare("CREATE TABLE IF NOT EXISTS migrations(
        id int PRIMARY KEY AUTO_INCREMENT, 
        migration VARCHAR(255), 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);");

        $statement->execute();
    }

    private function getAppliedMigrations(){
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getMigrations(){
        $migrations = scandir(Application::$ROOT_DIR. "/migrations");
        return array_filter($migrations, function($migration){
           return $migration !== "." && $migration !== "..";
        });
    }

    private function insertMigrations(array $migrations = []){
        $values = implode(",", array_map(function($migration){
            return "('$migration')";
        }, $migrations));

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $values");
        $statement->execute();
    }

    private function executeMigrations(array $migrations = []){
        foreach ($migrations as $migration){
            require_once Application::$ROOT_DIR . "/migrations/" . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $instance->up();
            $this->log($migration);

        }
    }

    private function log($migration){
        echo "[".date("Y-m-d h:i:s")."] applying migration $migration" . PHP_EOL;
    }

    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }
}