<?hh // strict

namespace kilahm\chores\handler;

use kilahm\chores\service\AuthGroup;
use kilahm\IOC\FactoryContainer;

class Room
{
    <<route('GET', '/room/(\d+)')>>
    public static function roomList(FactoryContainer $c, Vector<string> $matches) : void
    {
        $rsp = $c->getResponse();
        if(!$c->getAuth()->isUser()) {
            $rsp->forbidden();
            return;
        }

        $roomStore = $c->getRoomStore();
        $taskStore = $c->getTaskStore();

        $roomId = (int)$matches->at(1);

        $room = $roomStore->fromId($roomId);
        if($room === null) {
            $rsp->setCode(404);
            $rsp->setBody('Could not find room id ' . $roomId);
            return;
        }

        $taskList = $taskStore->fromRoom($room);
    }
}
