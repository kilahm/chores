<?hh // strict

namespace kilahm\chores\service;

use PDO;
use PDOStatement;

class DbResult
{
    private bool $needsToExecute = true;

    public function __construct(private PDOStatement $statement)
    {
    }

    public function one(Map<arraykey, string> $params = Map{}) : ?Map<string, string>
    {
        $data = $this->execute($params)->statement->fetch(PDO::FETCH_ASSOC);

        if(is_array($data)) {
            return new Map($data);
        }

        return null;
    }

    public function all(Map<arraykey, string> $params = Map{}) : Vector<Map<string, string>>
    {
        $data = $this->execute($params)->statement->fetchAll(PDO::FETCH_ASSOC);

        if(is_array($data)) {
            return (new Vector($data))->map($row ==> new Map($row));
        }

        return Vector{};
    }

    public function execute(Map<arraykey, string> $params = Map{}) : this
    {
        if($this->needsToExecute) {
            $this->needsToExecute = false;
            $this->statement->execute($params->toArray());
        }
        return $this;
    }
}
