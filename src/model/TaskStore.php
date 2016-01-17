<?hh // strict

namespace kilahm\chores\model;

use kilahm\chores\service\Db;
use kilahm\IOC\FactoryContainer;

type Task = shape(
    'id' => int,
    'name' => string,
);

final class TaskStore
{
    const string SCHEMA = <<<SQL
CREATE TABLE "task"
(
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" STRING,
    "roomId" INTEGER REFERENCES "room"
)
SQL;

    <<provides('TaskStore')>>
    public static function factory(FactoryContainer $c) : this
    {
        return new static($c->getDb());
    }

    public function __construct(private Db $db)
    {
    }

    public function fromRoom(Room $room) : Vector<Task>
    {
        return $this->db
            ->query('SELECT * FROM "task" WHERE "roomId" = :roomId')
            ->all(Map{':roomId' => field\IntField::toStore($room['id'])})
            ->map($row ==> $this->fromData($row))
            ;
    }

    private function fromData(Map<string, string> $data) : Task
    {
        return shape(
            'id' => field\IntField::fromStore($data->at('id')),
            'name' => $data->at('name'),
        );
    }

    private function toData(Task $task) : Map<string, string>
    {
        return Map{
            'id' => field\IntField::toStore($task['id']),
            'name' => $task['name'],
        };
    }
}
