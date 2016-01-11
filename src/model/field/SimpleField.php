<?hh // strict

namespace kilahm\chores\model\field;

<<__ConsistentConstruct>>
abstract class SimpleField<Tval> implements Field<Tval>
{
    public static function buildFromStore(string $value) : this
    {
        return new static(static::fromStore($value));
    }

    public function __construct(protected Tval $value)
    {
    }

    public function format() : string
    {
        return static::toStore($this->value);
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
