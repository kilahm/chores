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
    public function getDb() : \PDO
    {
        return $this->newDb();
    }

    public function newDb() : \PDO
    {
        return $this->runner->make(class_meth('\kilahm\chores\Factories', 'db'));
    }
}