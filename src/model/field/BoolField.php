<?hh // strict

namespace kilahm\chores\model\field;

use kilahm\chores\enum\SqlType;

abstract final class BoolField
{
    public static function fromStore(string $value) : bool
    {
        if($value === '0' || $value === '' || $value === 'false') {
            return false;
        }

        return true;
    }

    public static function toStore(bool $value) : string
    {
        return $value ? '1' : '0';
    }
}
