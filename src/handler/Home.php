<?hh // strict

namespace kilahm\chores\handler;

use kilahm\IOC\FactoryContainer;

class Home
{
    <<route('get', '/')>>
    public static function home(FactoryContainer $c, Vector<string> $matches) : void
    {
        $rsp = $c->getResponse();
        // TODO: if already logged in, show list of rooms
        $view = new \Login();
        $rsp->setBody($view->render());
    }
}
