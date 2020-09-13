<?php

namespace mradevelopers\phpmvc\db;

use app\migrations\m0001_initial;
use mradevelopers\phpmvc\Application;

class Database
{
    public \PDO $pdo;
    public function __construct(array $config)
    {
        $dns = $config['dns'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dns,$user,$password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION); 
    }

    public function applyMigrations()
    {
        $this->createMigrationTables();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT.'/migrations');

        $toApplyMigrations = array_diff($files,$appliedMigrations);

        foreach($toApplyMigrations as $migration)
        {
            if($migration == '.' || $migration == '..')
            {
                continue;
            }

            
           include_once Application::$ROOT.'/migrations/'.$migration;
      
            $className = pathinfo($migration,PATHINFO_FILENAME);

     
            $instance = new $className;

            $this->log("Applying migrations $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;

        }

        if(!empty($newMigrations))
        {
            $this->saveMigrations($newMigrations);
        }
        else
        {
           $this->log("All migrations are already applied");
        }

    }

    public function createMigrationTables()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $stmt = $this->pdo->prepare("SELECT  migration FROM migrations");
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        $str = implode(",",array_map(fn($m)=>"('$m')",$migrations));
      
        $stmt = $this->pdo->prepare("INSERT INTO migrations(migration) VALUES $str ");
        $stmt->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    protected function log($msg)
    {
        echo '['.date('Y-m-d H:i:s').'] - '.$msg.PHP_EOL;
    }

}





?>