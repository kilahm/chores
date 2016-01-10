<?hh // strict

class Login
{
    public function show() : void
    {
        echo
            <chores:root>
            <bootstrap:container>
                <bootstrap:page-header
                    title="Time to clean!"
                />
                <form class="form-horizontal" action="/login" method="post" >
                <div class="row">
                    <div class="col-md-5">
                        <label class="control-label" for="name">Your Name</label>
                        <input class="form-control" type="text" name="name" />
                    </div>
                    <div class="col-md-5 col-md-offset-2">
                        <label class="control-label" for="password">Password</label>
                        <input class="form-control" type="password" name="password" />
                    </div>
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
}
