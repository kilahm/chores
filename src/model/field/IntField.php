<?hh // strict

namespace kilahm\chores\model\field;

use kilahm\chores\enum\SqlType;

final class IntField extends SimpleField<int>
{
    public static function fromStore(string $value) : int
    {
        if(is_numeric($value)) {
            return (int)$value;
        }

        throw new \UnexpectedValueException('Int field received non-numeric value from store');
    }

    public static function toStore(int $value) : string
    {
        return (string)$value;
    }

    public function sqlType() : SqlType
    {
        return SqlType::Tint;
    }
}
