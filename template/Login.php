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
            <bootstrap:container>
                <bootstrap:page-header
                    title="Time to clean!"
                />
                <form class="form-horizontal" action="/login" method="post" >
                <div class="row">
                    {$this->nameField()}
                    {$this->passField()}
                </div>
                <div class="row">
                    <div
                        class="col-xs-12"
                        style="margin-top: 10px;"
                    >
                        <button class="btn btn-block btn-default" type="submit">Log In</button>
                    </div>
                </div>
                </form>
            </bootstrap:container>
            </chores:root>
        ;
    }

    private function nameField() : XHPRoot
    {
        $class = 'control-label';
        if($this->invalidFields->contains('name')) {
             $class .= ' has-error';
        }

        return
            <div class="col-md-5">
                <label class={$class} for="name">Your Name</label>
                <input class="form-control" type="text" name="name" />
            </div>
        ;
    }

    private function passField() : XHPRoot
    {
        $class = 'control-label';
        if($this->invalidFields->contains('pass')) {
             $class .= ' has-error';
        }

        return
            <div class="col-md-5">
                <label class={$class} for="password">Password</label>
                <input class="form-control" type="password" name="password" />
            </div>
        ;
    }
}
