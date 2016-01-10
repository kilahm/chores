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

    <<provides('db')>>
    public static function db(FactoryContainer $c) : \PDO
    {
        $settings = $c->getConfig()->dbSettings();
        return new \PDO($settings['dsn'], $settings['user'], $settings['password'], $settings['options']);
    }
}
