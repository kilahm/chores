<?hh // strict

namespace kilahm\chores\model\field;

use kilahm\chores\enum\SqlType;

final class StringField extends SimpleField<string>
{
    public function fromStore(string $value) : void
    {
        $this->value = $value;
    }

    public function toStore() : string
    {
        return $this->value;
    }

    public function sqlType() : SqlType
    {
        return SqlType::Tstring;
    }
}
