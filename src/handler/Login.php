<?hh // strict

namespace kilahm\chores\handler;

use kilahm\IOC\FactoryContainer;

class Login
{
    <<route('get', '/login')>>
    public static function handleLogin(FactoryContainer $c, Vector<string> $matches) : void
    {
        $req = $c->getRequest();
        $rsp = $c->getResponse();
        $session = $c->getSession();

        if($session->currentSession() === null) {
            $view = new \Login();
            $rsp->setBody($view->render());
            return;
        }

        $rsp->forward('/', 303);
    }

    <<route('post', '/login')>>
    public static function handleForm(FactoryContainer $c, Vector<string> $matches) : void
    {
        $req = $c->getRequest();
        $rsp = $c->getResponse();
        $userStore = $c->getUserStore();
        $session = $c->getSession();

        // Required fields
        $name = $req->post('name', FILTER_SANITIZE_STRING);
        $password = $req->post('password', FILTER_UNSAFE_RAW);

        if($name === null || $password === null) {
            $missing = Set{};
            if($name === null) {
                 $missing->add('name');
            }
            if($password === null) {
                 $missing->add('password');
            }
            $view = new \Login($missing);
            $rsp->setBody($view->render());
            return;
        }

        $user = $userStore->fromCreds($name, $password);
        if($user !== null) {
            $session->newSession($user);
            $rsp->forward('/', 303);
            return;
        }

        $view = new \Login();
        $rsp->setBody($view->render());
    }
}
