<?hh // strict

namespace kilahm\chores\model\field;

use DateTime;
use kilahm\chores\enum\SqlType;

final class DateTimeField extends SimpleField<DateTime>
{
    public static function now() : this
    {
        return new static(new DateTime('now', new \DateTimeZone('UTC')));
    }

    public static function fromStore(string $value) : DateTime
    {
        if(is_numeric($value)) {
            $intval = (int)$value;
            return new DateTime('@' . $intval, new \DateTimeZone('UTC'));
        }

        throw new \UnexpectedValueException('DateTime field received non-numeric value from store');
    }

    public static function toStore(DateTime $value) : string
    {
        return (string)$value->getTimestamp();
    }

    public function sqlType() : SqlType
    {
        return SqlType::Tint;
    }
}
