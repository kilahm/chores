<?hh // strict

namespace kilahm\chores\service;

use PDO;
use PDOStatement;

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

    public function __construct(private PDO $pdo)
    {
    }

    public function query(string $sql) : DbResult
    {
        $statement = $this->pdo->prepare($sql);
        if($statement instanceof PDOStatement) {
            return new DbResult($statement);
        }
        throw new \kilahm\chores\exception\Db($sql, $this->pdo);
    }
}
