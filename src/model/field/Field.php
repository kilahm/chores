<?hh // strict

namespace kilahm\chores\model\field;

use kilahm\chores\enum\SqlType;

interface Field<Tval>
{
    public static function fromStore(string $data) : Tval;
    public static function toStore(Tval $value) : string;
    public function set(Tval $newValue) : void;
    public function get() : Tval;
    public function sqlType() : SqlType;
}
