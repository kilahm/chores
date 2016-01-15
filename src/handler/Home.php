<?hh // strict

namespace kilahm\chores\handler;

use kilahm\IOC\FactoryContainer;

class Home
{
    <<route('get', '/')>>
    public static function home(FactoryContainer $c, Vector<string> $matches) : void
    {
        $rsp = $c->getResponse();
        $user = $c->getSession()->currentUser();

        if($user === null) {
            $view = new \Login();
            $rsp->setBody($view->render());
            return;
        }

        $view = new \RoomList($c->getRoomStore()->allRooms());
        $rsp->setBody($view->render());
    }
}
