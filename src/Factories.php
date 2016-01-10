<?hh // strict

namespace kilahm\chores;

use kilahm\IOC\FactoryContainer;

class Factories
{
    <<provides('router')>>
    public static function router(FactoryContainer $c) : \kilahm\AttributeRouter\Router
    {
        return new \kilahm\AttributeRouter\Router(new \AutoRoutes($c));
    }
}
