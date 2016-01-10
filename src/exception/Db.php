<?hh // strict

namespace kilahm\chores\exception;

class Db extends \Exception
{
    public function __construct(string $sql, \PDO $pdo)
    {
        $info = $pdo->errorInfo();
        parent::__construct(sprintf(
            "Error while executing %s\n SQLSTATE: %s\n %s",
            $sql,
            $info[0],
            $info[2]
        ));
    }
}
