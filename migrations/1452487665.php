<?hh // strict

namespace kilahm\chores\migration;

class Migration_1452487665 extends Migration
{
    public function description() : string
    {
        return 'First migration - create pre v1 tables and the root user';
    }

    public function run() : void
    {
        $initialSchema = [
            \kilahm\chores\model\UserStore::SCHEMA,
            \kilahm\chores\model\SessionStore::SCHEMA,
            \kilahm\chores\model\RoomStore::SCHEMA,
        ];

        foreach($initialSchema as $sql) {
            $this->db->query($sql)->execute();
        }

        $userStore = new \kilahm\chores\model\UserStore($this->db);
        $userStore->newUser('root', 'root');

        $roomStore = new \kilahm\chores\model\RoomStore($this->db);
        $roomStore->newRoom('Kitchen');
        $roomStore->newRoom('Livingroom');
        $roomStore->newRoom('Diningroom');
        $roomStore->newRoom('Bathroom');
        $roomStore->newRoom('Downstairs Hall');
        $roomStore->newRoom('Upstairs Hall');
        $roomStore->newRoom('Stairs');
    }
}
