<?hh // strict

namespace kilahm\chores\handler;

use kilahm\IOC\FactoryContainer;

class Login
{
    <<route('get', '/login')>>
    public static function handleLogin(FactoryContainer $c, Vector<string> $matches) : void
    {
        $view = new \Login();
        $view->show();
    }

    <<route('post', '/login')>>
    public static function handleForm(FactoryContainer $c, Vector<string> $matches) : void
    {
        var_dump(filter_input_array(
            INPUT_POST,
            [
                'name' => FILTER_SANITIZE_STRING,
                'password' => FILTER_SANITIZE_STRING,
            ],
            true, // add missing fields as null values
        ));
    }
}
