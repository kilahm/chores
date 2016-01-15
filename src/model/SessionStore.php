<?hh // strict

namespace kilahm\chores\model;

use kilahm\chores\service\Db;
use kilahm\IOC\FactoryContainer;

type Session = shape(
    'key' => string,
    'userId' => int,
    'ttl' => \DateTime,
);

final class SessionStore
{
    const SCHEMA = <<<SQL
CREATE TABLE IF NOT EXISTS "session"
(
    "key" PRIMARY KEY,
    "userId" INTEGER REFERENCES "user" ("id"),
    "ttl" INTEGER
)
WITHOUT ROWID
SQL;

    <<provides('SessionStore')>>
    public static function factory(FactoryContainer $c) : this
    {
        return new static($c->getDb());
    }

    public function __construct(private Db $db)
    {
    }

    public function fetchWithKey(?string $key) : ?Session
    {
        if($key === null) {
            return null;
        }

        $result = $this->db
            ->query('SELECT * FROM "session" WHERE "key" = :key')
            ->one(Map{':key' => $key});
        if($result === null) {
            return null;
        }

        return $this->fromData($result);
    }

    public function invalidate(Session $session) : void
    {
        $this->db->query('DELETE FROM session WHERE "key" = :key')
            ->execute(Map{':key' => $session['key']});
    }

    public function save(Session $session) : void
    {
        $data = QueryBuilder::mapToFieldsAndValues($this->toData($session));
        $sql = sprintf(
            'INSERT OR REPLACE INTO "session" (%s) VALUES (%s)',
            $data['field list'],
            $data['value list']
        );
        $this->db->query($sql)->execute($data['params']);
    }

    private function fromData(Map<string, string> $data) : Session
    {
        return shape(
            'key' => $data->at('key'),
            'userId' => field\IntField::fromStore($data->at('userId')),
            'ttl' => field\DateTimeField::fromStore($data->at('ttl')),
        );
    }

    private function toData(Session $data) : Map<arraykey,string>
    {
        return Map{
            'key' => $data['key'],
            'userId' => field\IntField::toStore($data['userId']),
            'ttl' => field\DateTimeField::toStore($data['ttl']),
        };
    }
}
