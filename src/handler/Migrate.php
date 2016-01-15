<?hh // strict

namespace kilahm\chores\handler;

use DateTime;
use kilahm\chores\Config;
use kilahm\chores\service\Migrator;
use kilahm\chores\service\Request;
use kilahm\chores\service\Response;
use kilahm\IOC\FactoryContainer;

final class Migrate
{
    <<route('get', '/migrate')>>
    public static function showMigrations(FactoryContainer $c, Vector<string> $matches) : void
    {
        (new static(
            $c->getRequest(),
            $c->getResponse(),
            $c->getMigrator(),
            $c->getConfig(),
            false,
        ))->run();
    }

    <<route('post', '/migrate')>>
    public static function runMigrations(FactoryContainer $c, Vector<string> $matches) : void
    {
        (new static(
            $c->getRequest(),
            $c->getResponse(),
            $c->getMigrator(),
            $c->getConfig(),
            true,
        ))->run();
    }


    public function __construct(
        private Request $req,
        private Response $rsp,
        private Migrator $migrator,
        private Config $config,
        private bool $shouldMigrate,
    )
    {
    }

    private function run() : void
    {
        if( ! $this->checkAuth()) {
            $this->rsp->setBody($this->config->notFoundMessage());
            $this->rsp->setCode(404);
            return;
        }

        $thisRun = $this->shouldMigrate ?
            $this->migrator->run() :
            Vector{};

        $view = new \Migrate($this->migrator->listAllMigrations(), $thisRun);
        $this->rsp->setBody($view->render());
    }

    private function checkAuth() : bool
    {
        $key = (string)$this->req->header('chores-migration-key');
        return password_verify($key, $this->config->migrationKey());

        // TODO: check session for super user
    }
}
