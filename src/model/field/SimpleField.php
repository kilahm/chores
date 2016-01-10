<?hh // strict

namespace kilahm\chores\model\field;

abstract class SimpleField<Tval> implements Field<Tval>
{
    public function __construct(protected Tval $value)
    {
    }

    public function set(Tval $newValue) : void
    {
        $this->value = $newValue;
    }

    public function get() : Tval
    {
        return $this->value;
    }
}
