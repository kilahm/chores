<?hh // strict

namespace kilahm\chores\service;

use kilahm\IOC\FactoryContainer;

final class Request
{
    <<provides('Request')>>
    public static function fromRequest(FactoryContainer $c) : this
    {
        return new static(
            getallheaders(),
            fopen('php://input', 'r'),
        );
    }

    private Map<string, string> $headers = Map{};

    public function __construct(
        KeyedTraversable<string, string> $headers,
        private resource $body,
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
}
