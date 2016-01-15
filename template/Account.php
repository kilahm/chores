<?hh // strict

class Account
{
    public function render() : string
    {
        return (string)
            <chores:root>
                <bootstrap:container>
                </bootstrap:container>
            </chores:root>
        ;
    }
}
