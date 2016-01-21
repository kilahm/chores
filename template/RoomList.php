<?hh // strict

use kilahm\chores\model\Room;

final class RoomList
{
    public function __construct(private Vector<Room> $roomList)
    {
    }

    public function render() : string
    {
        return (string)
            <chores:root>

                <chores:title>
                    All Rooms
                </chores:title>
                {$this->listOfRooms()}
            </chores:root>;
    }

    private function listOfRooms() : XHPRoot
    {
        if($this->roomList->isEmpty()) {
            return <chores:message>No rooms have been defined</chores:message>;
        }
        return
            <chores:list>
            {$this->roomList->map($room ==>
                <chores:list-item href={'/room/' . $room['id']} count={999} >
                    {$room['name']}
                </chores:list-item>
            )}
            </chores:list>;
    }
}
