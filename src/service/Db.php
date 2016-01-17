<?hh // strict

namespace kilahm\chores\service;

use PDO;
use PDOStatement;

/**
 * This PDO wrapper assumes SQLite is used
 */
<<__ConsistentConstruct>>
class Db
{
    <<provides('db')>>
    public static function db(\kilahm\IOC\FactoryContainer $c) : this
    {
        $settings = $c->getConfig()->dbSettings();
        $pdo = new PDO($settings['dsn'], $settings['user'], $settings['password'], $settings['options']);
        return new static($pdo);
    }

    private bool $locked = false;

    public function __construct(private PDO $pdo)
    {
        $this->enableForeignKeyChecks();
    }

    public function enableForeignKeyChecks() : void
    {
        $this->pdo->exec('PRAGMA foreign_keys = ON;');
    }

    public function disableForeignKeyChecks() : void
    {
        $this->pdo->exec('PRAGMA foreign_keys = OFF;');
    }

    public function query(string $sql) : DbResult
    {
        $statement = $this->pdo->prepare($sql);
        if($statement instanceof PDOStatement) {
            return new DbResult($statement);
        }
        throw new \kilahm\chores\exception\Db($sql, $this->pdo);
    }

    public function startTransaction(bool $exclusive = false) : bool
    {
        // No nested transactions
        if($this->locked) {
             return false;
        }

        $lockType = $exclusive ? 'EXCLUSIVE' : 'DEFERRED';
        $sql = sprintf('BEGIN %s TRANSACTION', $lockType);
        $this->locked = true;

        return $this->pdo->exec($sql) !== false;
    }

    public function commitTransaction() : bool
    {
        if($this->locked) {
            $this->locked = false;
            return $this->pdo->exec('COMMIT') !== false;
        }

        // Non-extant transactions can always be committed
        return true;
    }

    public function rollbackTransaction() : bool
    {
        if($this->locked) {
            $this->locked = false;
            return $this->pdo->exec('ROLLBACK') !== false;
        }

        // Non-extant transactions can never be rolled back
        return false;
    }

    public function lastInsertId() : ?string
    {
         $lastId = $this->pdo->lastInsertId();
         if(is_string($lastId)) {
             return $lastId;
         }
         return null;
    }
}
