<?hh // strict

use kilahm\AttributeRouter\Route;

/**
 * This file is generated using the scanroutes executable which looks for
 * routing attribute.
 *
 * To assign routes without using the routing attribute, edit
 * Routes.php
 */


type Route = shape(
    'pattern' => string,
    'method' => (function(kilahm\IOC\FactoryContainer, Vector<string>) : void),
);

class AutoRoutes extends Routes
{

    public function get() : Vector<Route>
    {
        return parent::get()->addAll(Vector
        {            shape(
                'pattern' => '#^/login$#',
                'method' => class_meth(\kilahm\chores\handler\Login::class, 'handleLogin'),
            ),
        });
    }

    public function post() : Vector<Route>
    {
        return parent::post()->addAll(Vector
        {            shape(
                'pattern' => '#^/login$#',
                'method' => class_meth(\kilahm\chores\handler\Login::class, 'handleForm'),
            ),
        });
    }

    public function put() : Vector<Route>
    {
        return parent::put()->addAll(Vector
        {
        });
    }

    public function delete() : Vector<Route>
    {
        return parent::delete()->addAll(Vector
        {
        });
    }

    public function any() : Vector<Route>
    {
        return parent::any()->addAll(Vector
        {
        });
    }

}