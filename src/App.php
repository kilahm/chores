<?hh // strict

namespace kilahm\chores;


class App
{
    public static function run() : void
    {
        $c = new \kilahm\IOC\FactoryContainer();
        $request = $c->getRequest();
        $router = $c->getRouter();
        $config = $c->getConfig();
        $response = $c->getResponse();


        try{
            $verb = $request->verb();
            if($verb === null) {
                // TODO: update attribute router to handle unknown verbs
                $verb = \kilahm\AttributeRouter\HttpVerb::Get;
            }

            $found = $router->match($request->uri(), $verb);
            if(!$found) {
                $response->setBody($config->notFoundMessage());
                $response->setCode(404);
            }

        } catch(\Exception $e) {
            $response->setBody(\Error::render($e));
            $response->setCode(500);

            if($config->isProduction()) {
                // TODO: Logging?
                $response->setBody('');
            }
        }

        http_response_code($response->getCode());

        foreach($response->headers() as $header) {
            header($header['name'] . ': ' . $header['value']);
        }

        foreach($response->cookies() as $cookie) {
            var_dump($cookie);
            setcookie(
                $cookie['key'],
                $cookie['body'],
                $cookie['ttl']->getTimestamp(),
                '/', // All of the domain
                $config->host(),
                $config->useSSL(),
                true, // HTTP only
            );
        }

        echo $response->body();
    }
}
