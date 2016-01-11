<?hh // strict

namespace kilahm\chores\service;

use kilahm\chores\model\MigrationStore;
use kilahm\chores\model\field\StringField;
use kilahm\IOC\FactoryContainer;

final class Migrator
{
    <<provides('Migrator')>>
    public static function factory(FactoryContainer $c) : this
    {
        $config = $c->getConfig();
        return new static(
            $c->getMigrationStore(),
            $config->projectPath('migrations'),
            $c->getDb(),
        );
    }

    public function __construct(
        private MigrationStore $store,
        private string $migrationPath,
        private \kilahm\chores\service\Db $db,
    )
    {
    }

    public function run() : Vector<\kilahm\chores\model\Migration>
    {
        $thisRun = Vector{};
        try{
            $this->store->lock();
            foreach($this->newMigrations() as $migration) {
                $record = $this->store->fromMigrationObject($migration);
                $record['start'] = \kilahm\chores\model\field\DateTimeField::now();
                $migration->run();
                $record['end'] = \kilahm\chores\model\field\DateTimeField::now();
                $this->store->save($record);
                $thisRun->add($record);
            }
            $this->store->release();
            return $thisRun;
        } catch (\Exception $e) {
            $this->store->abort();
            throw $e;
        }
    }

    public function listAllMigrations() : Vector<\kilahm\chores\model\Migration>
    {
        $all = $this->store->fetchFinishedMigrations();

        $finished = $all->map($m ==> $m['signature']->get())->toSet();

        $defined = $this->findDefinedMigrations();
        foreach($defined as $migration) {
            if(!$finished->contains($migration->signature())) {
                $all->add($this->store->fromMigrationObject($migration));
            }
        }

        return $all;
    }

    <<__Memoize>>
    private function findDefinedMigrations() : Vector<\kilahm\chores\migration\Migration>
    {
        $migrations = Vector{};

        $di = new \DirectoryIterator($this->migrationPath);
        foreach($di as $info) {
            $migrationNumber = $info->getBasename('.php');
            if($info->isFile() && $info->isReadable() && is_numeric($migrationNumber)) {
                $migration = $this->loadMigration($migrationNumber);
                if($migration !== null) {
                    $migrations->add($migration);
                }
            }
        }

        return $migrations;
    }

    private function loadMigration(string $migrationNumber) : ?\kilahm\chores\migration\Migration
    {
        $className = 'kilahm\chores\migration\Migration_' . $migrationNumber;
        $mirror = new \ReflectionClass($className);
        return $mirror->newInstance($this->db);
    }

    private function newMigrations() : Vector<\kilahm\chores\migration\Migration>
    {
        $finished = $this->store->fetchFinishedMigrations()->map($m ==> $m['signature']->get())->toSet();
        $defined = $this->findDefinedMigrations();
        return $defined->filter($m ==> !$finished->contains($m->signature()));
    }
}
