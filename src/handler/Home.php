<?hh // strict

namespace kilahm\chores\handler;

use kilahm\IOC\FactoryContainer;

class Home
{
    <<route('get', '/')>>
    public static function home(FactoryContainer $c, Vector<string> $matches) : void
    {
        $rsp = $c->getResponse();

        $view = $c->getAuth()->isUser() ?
            new \RoomList($c->getRoomStore()->allRooms()) :
            new \Login();

        $rsp->setBody($view->render());
    }
}
