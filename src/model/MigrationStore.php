<?hh // strict

namespace kilahm\chores\model;

use kilahm\chores\service\Db;
use kilahm\IOC\FactoryContainer;

type Migration = shape(
    'signature' => string,
    'description' => string,
    'start' => ?\DateTime,
    'end' => ?\DateTime,
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
            'signature' => $migration->signature(),
            'description' => $migration->description(),
            'start' => null,
            'end' => null,
        );
    }

    public function saveNew(Migration $migration) : void
    {
        $values = $this->toData($migration);
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
        $data = $this->toData($migration);
        list($fieldList, $params) = QueryBuilder::mapToUpdateList($data);

        $sql = sprintf(
            'UPDATE "migration" SET %s WHERE "signature" = :signature',
            $fieldList
        );

        $this->db->query($sql)->execute($params);
    }

    private function fromData(Map<string, string> $data) : Migration
    {
        $start = $data->get('start');
        $end = $data->get('end');

        if(is_string($start) && is_numeric($start)) {
            $start = field\DateTimeField::fromStore($start);
        } else {
             $start = null;
        }

        if(is_string($end) && is_numeric($end)) {
            $end = field\DateTimeField::fromStore($end);
        } else {
             $end = null;
        }

        return shape(
            'signature' => $data->at('signature'),
            'description' => $data->at('description'),
            'start' => $start,
            'end' => $end,
        );
    }

    private function toData(Migration $data) : Map<arraykey, string>
    {
        $values = Map{
            'description' => $data['description'],
            'signature' => $data['signature'],
        };

        $start = $data['start'];
        if($start !== null) {
             $values->set('start', field\DateTimeField::toStore($start));
        }

        $end = $data['end'];
        if($end !== null) {
             $values->set('end', field\DateTimeField::toStore($end));
        }

        return $values;
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
