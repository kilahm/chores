<?hh // strict

namespace kilahm\chores\handler;

use DateTime;
use kilahm\IOC\FactoryContainer;

final class Migrate
{
    <<route('get', '/migrate')>>
    public static function showMigrations(FactoryContainer $c, Vector<string> $matches) : void
    {
        $migrator = $c->getMigrator();
        $view = new \Migrate($migrator->listAllMigrations());
        $view->show();
    }

    <<route('post', '/migrate')>>
    public static function runMigrations(FactoryContainer $c, Vector<string> $matches) : void
    {
        $migrator = $c->getMigrator();
        $migrator->run();
        $view = new \Migrate($migrator->listAllMigrations());
        $view->show();
    }


}
