<?hh // strict

namespace kilahm\chores;

use kilahm\AttributeRouter\HttpVerb;

class App
{
    public static function run() : void
    {
        $verb = self::getVerb();
        if($verb === null) {
            echo 'Unknown verb';
            exit();
        }

        $c = new \kilahm\IOC\FactoryContainer();
        $router = $c->getRouter();
        $config = $c->getConfig();

        try{
            $found = $router->match(self::getRoute(), $verb);
            if(!$found) {
                http_response_code(404);
                echo 'Resource not found';
            }
        } catch(\Exception $e) {
            if($config->isProduction()) {
                // TODO: logging?
                exit();
            }
            \Error::show($e);
        }
    }

    private static function getRoute() : string
    {
        $raw = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        if(is_string($raw)) {
            return $raw;
        }
        return '';
    }

    private static function getVerb() : ?HttpVerb
    {
        $raw = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW);
        return HttpVerb::coerce($raw);
    }
}
