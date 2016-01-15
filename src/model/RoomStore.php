<?hh // strict

namespace kilahm\chores\model;

use kilahm\IOC\FactoryContainer;
use kilahm\chores\service\Db;

type Room = shape(
    'id' => int,
    'name' => string,
);

final class RoomStore
{
    const string TABLE = 'room';

    const string SCHEMA = <<<SQL
CREATE TABLE IF NOT EXISTS "room"
(
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" STRING
);
SQL;

    <<provides('RoomStore')>>
    public function factory(FactoryContainer $c) : this
    {
        return new static($c->getDb());
    }

    public function __construct(private Db $db)
    {
    }

    public function fromId(int $id) : ?Room
    {
        return $this->fromStore(
            $this->db->query(sprintf(
                'SELECT * FROM %s WHERE "id" = :id',
                self::TABLE
            ))->one(Map{':id' => (string)$id})
        );
    }

    public function newRoom(string $name) : void
    {
        $this->db->query('INSERT INTO "room" ("name") VALUES (:name)')
            ->execute(Map{':name' => $name});
    }

    private function fromStore(?Map<string, string> $data) : ?Room
    {
        if($data === null) {
            return null;
        }

        return shape(
            'id' => field\IntField::fromStore($data->at('int')),
            'name' => (string)$data->get('name'),
        );
    }

    private function toStore(Room $data) : Map<string,string>
    {
        return Map{
            'id' => field\IntField::toStore($data['id']),
            'name' => $data['name'],
        };
    }
}
