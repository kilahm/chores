<?hh // strict

namespace kilahm\chores\model\field;

use kilahm\chores\enum\SqlType;

final class BoolField extends SimpleField<bool>
{
    public function fromStore(string $value) : void
    {
        if($value === '0' || $value === '' || $value === 'false') {
            $this->value = false;
        }

        $this->value = true;
    }

    public function toStore() : string
    {
        return $this->value ? '1' : '0';
    }

    public function sqlType() : SqlType
    {
        return SqlType::Tint;
    }
}
