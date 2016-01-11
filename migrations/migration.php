<?hh // strict

namespace kilahm\chores\migration;

abstract class Migration
{
    public function __construct(protected \kilahm\chores\service\Db $db)
    {
    }

    public function signature() : string
    {
        $matches = [];
        preg_match('/(Migration_\d+)/', static::class, $matches);
        return $matches[1];
    }

    abstract public function description() : string;
    abstract public function run() : void;
}
