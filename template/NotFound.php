<?hh // strict

class NotFound
{
    public static function render() : string
    {
        return (string)
            <chores:root>
                <h1>We couldn't find the thing you were looking for</h1>
            </chores:root>;
    }
}
