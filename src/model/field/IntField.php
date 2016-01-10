<?hh // strict

namespace kilahm\chores\model\field;

use kilahm\chores\enum\SqlType;

final class IntField extends SimpleField<int>
{
    public function fromStore(string $value) : void
    {
        if(is_numeric($value)) {
            $this->value = (int)$value;
            return;
        }

        throw new \UnexpectedValueException('Int field received non-numeric value from store');
    }

    public function toStore() : string
    {
        return (string)$this->value;
    }

    public function sqlType() : SqlType
    {
        return SqlType::Tint;
    }
}
