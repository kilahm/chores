<?hh // strict

namespace kilahm\IOC;

class FactoryContainer
{
    protected FactoryRunner<FactoryContainer> $runner;

    public function __construct()
    {
        $this->runner = new FactoryRunner();
        $this->runner->setContainer($this);
    }

    <<__Memoize>>
    public function getConfig() : \kilahm\chores\Config
    {
        return $this->newConfig();
    }

    public function newConfig() : \kilahm\chores\Config
    {
        return $this->runner->make(class_meth('\kilahm\chores\Config', 'factory'));
    }

    <<__Memoize>>
    public function getRouter() : \kilahm\AttributeRouter\Router
    {
        return $this->newRouter();
    }

    public function newRouter() : \kilahm\AttributeRouter\Router
    {
        return $this->runner->make(class_meth('\kilahm\chores\Factories', 'router'));
    }

    <<__Memoize>>
    public function getMigrationStore() : \kilahm\chores\model\MigrationStore
    {
        return $this->newMigrationStore();
    }

    public function newMigrationStore() : \kilahm\chores\model\MigrationStore
    {
        return $this->runner->make(class_meth('\kilahm\chores\model\MigrationStore', 'factory'));
    }

    <<__Memoize>>
    public function getRoomStore() : \kilahm\chores\model\RoomStore
    {
        return $this->newRoomStore();
    }

    public function newRoomStore() : \kilahm\chores\model\RoomStore
    {
        return $this->runner->make(class_meth('\kilahm\chores\model\RoomStore', 'factory'));
    }

    <<__Memoize>>
    public function getSessionStore() : \kilahm\chores\model\SessionStore
    {
        return $this->newSessionStore();
    }

    public function newSessionStore() : \kilahm\chores\model\SessionStore
    {
        return $this->runner->make(class_meth('\kilahm\chores\model\SessionStore', 'factory'));
    }

    <<__Memoize>>
    public function getUserStore() : \kilahm\chores\model\UserStore
    {
        return $this->newUserStore();
    }

    public function newUserStore() : \kilahm\chores\model\UserStore
    {
        return $this->runner->make(class_meth('\kilahm\chores\model\UserStore', 'factory'));
    }

    <<__Memoize>>
    public function getDb() : \kilahm\chores\service\Db
    {
        return $this->newDb();
    }

    public function newDb() : \kilahm\chores\service\Db
    {
        return $this->runner->make(class_meth('\kilahm\chores\service\Db', 'db'));
    }

    <<__Memoize>>
    public function getMigrator() : \kilahm\chores\service\Migrator
    {
        return $this->newMigrator();
    }

    public function newMigrator() : \kilahm\chores\service\Migrator
    {
        return $this->runner->make(class_meth('\kilahm\chores\service\Migrator', 'factory'));
    }

    <<__Memoize>>
    public function getRequest() : \kilahm\chores\service\Request
    {
        return $this->newRequest();
    }

    public function newRequest() : \kilahm\chores\service\Request
    {
        return $this->runner->make(class_meth('\kilahm\chores\service\Request', 'fromRequest'));
    }

    <<__Memoize>>
    public function getResponse() : \kilahm\chores\service\Response
    {
        return $this->newResponse();
    }

    public function newResponse() : \kilahm\chores\service\Response
    {
        return $this->runner->make(class_meth('\kilahm\chores\service\Response', 'factory'));
    }

    <<__Memoize>>
    public function getSession() : \kilahm\chores\service\Session
    {
        return $this->newSession();
    }

    public function newSession() : \kilahm\chores\service\Session
    {
        return $this->runner->make(class_meth('\kilahm\chores\service\Session', 'factory'));
    }
}