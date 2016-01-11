<?hh // strict

namespace kilahm\chores\model\field;

use kilahm\chores\enum\SqlType;

final class StringField extends SimpleField<string>
{
    public static function fromStore(string $value) : string
    {
        return $value;
    }

    public static function toStore(string $value) : string
    {
        return $value;
    }

    public function sqlType() : SqlType
    {
        return SqlType::Tstring;
    }
}
