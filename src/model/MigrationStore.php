<?hh // strict

namespace kilahm\chores\model;

use kilahm\chores\service\Db;
use kilahm\IOC\FactoryContainer;

type Migration = shape(
    'signature' => field\StringField,
    'description' => field\StringField,
    'start' => ?field\DateTimeField,
    'end' => ?field\DateTimeField,
);

final class MigrationStore
{
    <<provides('MigrationStore')>>
    public static function factory(FactoryContainer $c) : this
    {
        return new static($c->getDb());
    }

    const SCHEMA = <<<SQL
CREATE TABLE "migration" (
    "signature" PRIMARY KEY ON CONFLICT ROLLBACK,
    "start" INTEGER,
    "end" INTEGER,
    "description" STRING
);
SQL;

    public function __construct(private Db $db)
    {
    }

    public function fetchFinishedMigrations() : Vector<Migration>
    {
        $this->ensureTableExists();
        return $this->db->query('SELECT * FROM "migration"')
            ->all()
            ->map($row ==> $this->fromData($row))
        ;
    }

    public function fromMigrationObject(\kilahm\chores\migration\Migration $migration) : Migration
    {
        return shape(
            'signature' => new field\StringField($migration->signature()),
            'description' => new field\StringField($migration->description()),
            'start' => null,
            'end' => null,
        );
    }

    public function save(Migration $migration) : void
    {
        $existing = $this->db->query('SELECT * FROM migration WHERE "signature" = :signature')
            ->one(Map{':signature' => $migration['signature']->format()});
        if($existing === null) {
            $this->saveNew($migration);
            return;
        }
        $this->update($migration);
    }

    private function saveNew(Migration $migration) : void
    {
        $values = Map{
            'signature' => $migration['signature']->format(),
            'description' => $migration['description']->format(),
        };

        $start = $migration['start'];
        if($start !== null) {
             $values->set('start', $start->format());
        }

        $end = $migration['end'];
        if($end !== null) {
             $values->set('end', $end->format());
        }

        $fieldsAndValues = QueryBuilder::mapToFieldsAndValues($values);
        $sql = sprintf(
            'INSERT INTO "migration" (%s) VALUES (%s)',
            $fieldsAndValues['field list'],
            $fieldsAndValues['value list'],
        );

        $this->db->query($sql)->execute($fieldsAndValues['params']);
    }

    public function update(Migration $migration) : void
    {
        $values = Map{
            'description' => $migration['description']->format(),
        };

        $start = $migration['start'];
        if($start !== null) {
             $values->set('start', $start->format());
        }

        $end = $migration['end'];
        if($end !== null) {
             $values->set('end', $end->format());
        }

        list($fieldList, $params) = QueryBuilder::mapToUpdateList($values);
        $sql = sprintf(
            'UPDATE "migration" SET %s WHERE "signature" = :signature',
            $fieldList
        );

        $params->set(':signature', $migration['signature']->format());

        $this->db->query($sql)->execute($params);
    }

    private function fromData(Map<string, string> $data) : Migration
    {
        $start = $data->get('start');
        $end = $data->get('end');

        if(is_string($start) && is_numeric($start)) {
            $start = field\DateTimeField::buildFromStore($start);
        } else {
             $start = null;
        }

        if(is_string($end) && is_numeric($end)) {
            $end = field\DateTimeField::buildFromStore($end);
        } else {
             $end = null;
        }

        return shape(
            'signature' => field\StringField::buildFromStore($data->at('signature')),
            'description' => field\StringField::buildFromStore($data->at('description')),
            'start' => $start,
            'end' => $end,
        );
    }

    <<__Memoize>>
    private function ensureTableExists() : void
    {
        $result = $this->db->query('SELECT "name" FROM sqlite_master WHERE "name" = :name AND "type" = :type')
            ->all(Map{
                'name' => 'migration',
                'type' => 'table'
            });

        if($result->isEmpty()) {
            $this->db->query(self::SCHEMA)->execute();
        }
    }

    public function startMigration(Migration $migration) : void
    {
        $this->db->query('UPDATE "migration" SET "start" = :time WHERE "signature" = :signature')
            ->execute(Map{
                ':time' => (string)time(),
                ':signature' => $migration['signature']->format(),
            });
    }

    public function endMigration(Migration $migration) : void
    {
        $this->db->query('UPDATE "migration" SET "end" = :time, "description" = :description WHERE "signature" = :signature')
            ->execute(Map{
                ':time' => (string)time(),
                ':signature' => $migration['signature']->format(),
                ':description' => $migration['description']->format(),
            });
    }

    public function lock() : void
    {
         $this->db->startTransaction(true);
    }

    public function release() : void
    {
        $this->db->commitTransaction();
    }

    public function abort() : void
    {
         $this->db->rollbackTransaction();
    }
}
