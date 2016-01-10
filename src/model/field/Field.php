<?hh // strict

namespace kilahm\chores\model\field;

use kilahm\chores\enum\SqlType;

interface Field<Tval>
{
    public function fromStore(string $data) : void;
    public function toStore() : string;
    public function set(Tval $newValue) : void;
    public function get() : Tval;
    public function sqlType() : SqlType;
}
