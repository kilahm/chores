<?hh // strict

use kilahm\chores\model\Room;

class ManageRooms
{
    public function __construct(
        private Vector<Room> $rooms,
        private Vector<string> $errors = Vector{},
    )
    {
    }

    public function render() : string
    {
        return (string)
            <chores:root>
                <chores:title>
                    Manage Rooms
                </chores:title>

                {$this->errors->map($e ==> <chores:error>{$e}</chores:error>)}

                {$this->roomList()}

                <form action="/manage/room/new" method="post">
                    <chores:input
                        type="text"
                        name="name"
                        placeholder="Room Name"
                    />
                    <chores:submit-button>
                        Create New Room
                    </chores:submit-button>
                </form>

            </chores:root>;
    }

    private function roomList() : XHPRoot
    {
        if($this->rooms->isEmpty()) {
            return <chores:message>No rooms have been defined</chores:message>;
        }

        return
            <chores:list>
                {$this->rooms->map($room ==>
                    <chores:list-item
                        href={'/manage/room/' . $room['id']}
                    >
                        {$room['name']}
                    </chores:list-item>
                )}
            </chores:list>;
    }
}
