<?hh // strict

namespace kilahm\chores\handler;

use kilahm\chores\service\AuthGroup;
use kilahm\IOC\FactoryContainer;

class ManageRoom
{
    <<route('GET', '/manage/room')>>
    public static function showForm(FactoryContainer $c, Vector<string> $matches) : void
    {
        $rsp = $c->getResponse();

        if( ! $c->getAuth()->check(AuthGroup::Admin) ) {
            $rsp->forbidden();
            return;
        }

        $view = new \ManageRooms($c->getRoomStore()->allRooms());
        $rsp->setBody($view->render());
    }

    <<route('POST', '/manage/room/new')>>
    public static function addRoom(FactoryContainer $c, Vector<string> $matches) : void
    {
        $rsp = $c->getResponse();
        $req = $c->getRequest();
        $roomStore = $c->getRoomStore();

        $name = $req->post('name', FILTER_SANITIZE_STRING);

        if( ! $c->getAuth()->check(AuthGroup::Admin) ) {
            $rsp->forbidden();
            return;
        }

        $errors = Vector{};
        if($name === null || $name === '') {
            $errors->add('You must supply a room name.');
        } else {
            $roomStore->newRoom($name);
        }

        $view = new \ManageRooms(
            $c->getRoomStore()->allRooms(),
            $errors,
        );

        $rsp->setBody($view->render());
    }

    <<route('GET', '/manage/room/(\d+)')>>
    public static function singleRoomForm(FactoryContainer $c, Vector<string> $matches) : void
    {
        $rsp = $c->getResponse();

        if( ! $c->getAuth()->check(AuthGroup::Admin) ) {
            $rsp->forbidden();
            return;
        }

        $roomId = (int)$matches->at(1);
        $roomStore = $c->getRoomStore();
        $taskStore = $c->getTaskStore();

        $room = $roomStore->fromId($roomId);
        if($room === null) {
            $rsp->notFound();
            return;
        }

        $tasks = $taskStore->fromRoom($room);
        $view = new \ManageRoom($room, $tasks);
        $rsp->setBody($view->render());
        return;
    }
}
