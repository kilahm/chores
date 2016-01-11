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
        return new static($c->getMigrationStore());
    }

    public function __construct(private MigrationStore $store)
    {
    }

    public function run() : void
    {
        try{
            $this->store->lock();
            foreach($this->newMigrations() as $migration) {
                $record = $this->store->fromMigrationObject($migration);
                $record['start'] = \kilahm\chores\model\field\DateTimeField::now();
                $migration->run();
                $record['end'] = \kilahm\chores\model\field\DateTimeField::now();
                $this->store->save($record);
            }
            $this->store->release();
        } catch (\Exception $e) {
            $this->store->abort();
            throw $e;
        }
    }

    public function listAllMigrations() : Vector<\kilahm\chores\model\Migration>
    {
        $all = $this->store->fetchFinishedMigrations();

        $finished = $all->map($m ==> $m['signature'])->toSet();

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
        // TODO: glob the migrations directory for the classes
        return Vector{};
    }

    private function newMigrations() : Vector<\kilahm\chores\migration\Migration>
    {
        $finished = $this->store->fetchFinishedMigrations()->map($m ==> $m['signature'])->toSet();
        $defined = $this->findDefinedMigrations();
        return $defined->filter($m ==> !$finished->contains($m->signature()));
    }
}
