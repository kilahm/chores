<?hh // strict

class Login
{
    public function __construct(
        private Set<string> $invalidFields = Set{},
    )
    {
    }

    public function render() : string
    {
        return (string)
            <chores:root>
                <chores:title>
                    Time to clean!
                </chores:title>
                <form action="/login" method="post" >
                    <chores:input
                        type="text"
                        name="name"
                        label="Your Name"
                    />
                    <chores:input
                        type="password"
                        name="password"
                        label="Password"
                    />
                    <chores:submit-button />
                </form>
            </chores:root>
        ;
    }
}
