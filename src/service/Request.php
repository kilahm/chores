<?hh // strict

namespace kilahm\chores\service;

use kilahm\AttributeRouter\HttpVerb;
use kilahm\IOC\FactoryContainer;

<<__ConsistentConstruct>>
class Request
{
    <<provides('Request')>>
    public static function fromRequest(FactoryContainer $c) : this
    {
        return new static(
            getallheaders(),
        );
    }

    private Map<string, string> $headers = Map{};

    public function __construct(
        KeyedTraversable<string, string> $headers,
    )
    {
        foreach($headers as $key => $value) {
            $this->headers->set(strtolower($key), $value);
        }
    }

    public function header(string $key) : ?string
    {
        return $this->headers->get(strtolower($key));
    }

    public function cookie(string $key, int $filterType = FILTER_UNSAFE_RAW) : ?string
    {
        return $this->simpleFilter(INPUT_COOKIE, $key, $filterType);
    }

    public function post(string $key, int $filterType) : ?string
    {
        return $this->simpleFilter(INPUT_POST, $key, $filterType);
    }

    public function uri() : string
    {
        $raw = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        if(is_string($raw)) {
            return $raw;
        }
        return '';
    }

    public function verb() : ?HttpVerb
    {
        $raw = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW);
        return HttpVerb::coerce($raw);
    }

    private function simpleFilter(int $inputType, string $key, int $filterType) : ?string
    {
        $result = filter_input($inputType, $key, $filterType);
        if(is_string($result)) {
            return $result;
        }
        return null;
    }
}
