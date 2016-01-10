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
}