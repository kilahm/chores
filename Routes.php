<?hh // strict

use kilahm\AttributeRouter\Route;

/**
 * If you would like to define some routes without using attributes, put them in this file.
 */

<<__ConsistentConstruct>>
class Routes
{
    public function __construct(private \kilahm\IOC\FactoryContainer $container)
    {
    }

    public function getContainer() : \kilahm\IOC\FactoryContainer
    {
        return $this->container;
    }

    public function get() : Vector<Route>
    {
        return Vector
        {
        /*
        shape(
            'pattern' => '/some regex here/',
            'method' => class_meth('\full\path\to\class', 'methodName'),
        ),
        shape(
            ...
        ),
        ...
        */
        };
    }

    public function put() : Vector<Route>
    {
        return Vector
        {
        };
    }

    public function delete() : Vector<Route>
    {
        return Vector
        {
        };
    }

    public function post() : Vector<Route>
    {
        return Vector
        {
        };
    }

    public function any() : Vector<Route>
    {
        return Vector
        {
        };
    }
}
