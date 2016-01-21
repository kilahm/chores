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

                <chores:list>
                {$this->roomList->map($room ==>
                    <chores:list-item href={'/room/' . $room['id']} count={999} >
                        {$room['name']}
                    </chores:list-item>
                )}
                </chores:list>

            </chores:root>;
    }
}
