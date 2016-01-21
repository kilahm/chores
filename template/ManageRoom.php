<?hh // strict

use kilahm\chores\model\Room;
use kilahm\chores\model\Task;

class ManageRoom
{
    public function __construct(
        private Room $room,
        private Vector<Task> $tasks,
    )
    {
    }

    public function render() : string
    {
        return (string)
            <chores:root>

                <chores:title
                    back="/manage/room"
                >
                    Manage {$this->room['name']}
                </chores:title>

                {$this->choresList()}

                <form action="/manage/task/add" method="post">
                    <chores:input
                        type="text"
                        name="name"
                        placeholder="Task Name"
                    />
                    <chores:submit-button>Add Task</chores:submit-button>
                </form>

            </chores:root>;
    }

    private function choresList() : XHPRoot
    {
        if($this->tasks->isEmpty()) {
            return
                <chores:message>
                    No tasks exists for {$this->room['name']}
                </chores:message>;
        }

        return
            <chores:list>
                {$this->tasks->map($task ==>
                <chores:list-item>$task['name']</chores:list-item>
                )}
            </chores:list>;
    }
}
