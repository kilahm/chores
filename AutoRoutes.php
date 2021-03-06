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
                'pattern' => '#^/$#',
                'method' => class_meth(\kilahm\chores\handler\Home::class, 'home'),
            ),            shape(
                'pattern' => '#^/login$#',
                'method' => class_meth(\kilahm\chores\handler\Login::class, 'handleLogin'),
            ),            shape(
                'pattern' => '#^/manage/room$#',
                'method' => class_meth(\kilahm\chores\handler\ManageRoom::class, 'showForm'),
            ),            shape(
                'pattern' => '#^/manage/room/(\d+)$#',
                'method' => class_meth(\kilahm\chores\handler\ManageRoom::class, 'singleRoomForm'),
            ),            shape(
                'pattern' => '#^/migrate$#',
                'method' => class_meth(\kilahm\chores\handler\Migrate::class, 'showMigrations'),
            ),            shape(
                'pattern' => '#^/room/(\d+)$#',
                'method' => class_meth(\kilahm\chores\handler\Room::class, 'roomList'),
            ),
        });
    }

    public function post() : Vector<Route>
    {
        return parent::post()->addAll(Vector
        {            shape(
                'pattern' => '#^/login$#',
                'method' => class_meth(\kilahm\chores\handler\Login::class, 'handleForm'),
            ),            shape(
                'pattern' => '#^/manage/room/new$#',
                'method' => class_meth(\kilahm\chores\handler\ManageRoom::class, 'addRoom'),
            ),            shape(
                'pattern' => '#^/migrate$#',
                'method' => class_meth(\kilahm\chores\handler\Migrate::class, 'runMigrations'),
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