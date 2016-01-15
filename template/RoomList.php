<?hh // strict

use kilahm\chores\model\Room;

class RoomList
{
    public function __construct(private Vector<Room> $roomList)
    {
    }

    public function render() : string
    {
        return (string)
            <chores:root>
                <bootstrap:container>

                    <chores:top-head>
                        All Rooms
                    </chores:top-head>

                    <bootstrap:list-group>
                    {$this->roomList->map($room ==>
                        <bootstrap:list-group-item href={'/room/' . $room['id']}>
                            {$room['name']}
                        </bootstrap:list-group-item>
                    )}
                    </bootstrap:list-group>

                </bootstrap:container>
            </chores:root>
            ;
    }
}
