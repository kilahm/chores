<?hh // strict

namespace kilahm\chores\handler;

use DateTime;
use kilahm\chores\Config;
use kilahm\chores\service\Request;
use kilahm\IOC\FactoryContainer;

final class Migrate
{
    <<route('get', '/migrate')>>
    public static function showMigrations(FactoryContainer $c, Vector<string> $matches) : void
    {
        self::checkAuth($c->getRequest(), $c->getConfig());
        $migrator = $c->getMigrator();
        $view = new \Migrate($migrator->listAllMigrations());
        $view->show();
    }

    <<route('post', '/migrate')>>
    public static function runMigrations(FactoryContainer $c, Vector<string> $matches) : void
    {
        self::checkAuth($c->getRequest(), $c->getConfig());
        $migrator = $c->getMigrator();
        $thisRun = $migrator->run();
        $view = new \Migrate($migrator->listAllMigrations(), $thisRun);
        $view->show();
    }

    private static function checkAuth(Request $req, Config $config) : void
    {
        $key = (string)$req->header('chores-migration-key');
        if(password_verify($key, $config->migrationKey())) {
            return;
        }

        http_response_code(404);
        exit();
    }
}
