<?hh // strict

namespace kilahm\chores\service;

type Cookie = shape(
    'key' => string,
    'body' => string,
    'ttl' => \DateTime,
);

type Header = shape(
    'name' => string,
    'value' => string,
);

<<__ConsistentConstruct>>
class Response
{
    <<provides('Response')>>
    public static function factory(\kilahm\IOC\FactoryContainer $c) : this
    {
        return new static();
    }

    private string $body = '';
    private Vector<Cookie> $cookies = Vector{};
    private Vector<Header> $headers = Vector{};
    private int $code = 200;
    private ?string $forward = null;

    public function setBody(string $body) : void
    {
        $this->body = $body;
    }

    public function body() : string
    {
         return $this->body;
    }

    public function setCode(int $code) : void
    {
        if($code < 100 || $code > 599) {
            throw new \Exception('Invalid HTTP response code');
        }
        $this->code = $code;
    }

    public function code() : int
    {
         return $this->code;
    }

    public function addCookie(string $key, string $body, \DateTime $ttl) : void
    {
        $this->cookies->add(shape(
            'key' => $key,
            'body' => $body,
            'ttl' => $ttl,
        ));
    }

    public function setCookie(string $key, string $body, \DateTime $ttl) : void
    {
        $this->removeCookie($key);
        $this->addCookie($key, $body, $ttl);
    }

    public function removeCookie(string $key) : void
    {
         $this->cookies = $this->cookies->filter($c ==> $c['key'] !== $key);
    }

    public function cookies() : Traversable<Cookie>
    {
         return $this->cookies;
    }

    public function addHeader(string $name, string $value) : void
    {
        $this->headers->add(shape(
            'name' => strtolower(rtrim($name, ':')),
            'value' => $value,
        ));
    }

    public function removeHeader(string $name) : void
    {
         $this->headers = $this->headers->filter($h ==> $h['name'] !== $name);
    }

    public function setHeader(string $name, string $value) : void
    {
        $this->removeHeader($name);
        $this->addHeader($name, $value);
    }

    public function headers() : Traversable<Header>
    {
        return $this->headers;
    }

    public function forward(string $url, int $code) : void
    {
        if($code < 300 || $code > 399) {
            throw new \InvalidArgumentException('Response code for redirects must be in the 3XX range');
        }
        $this->code = $code;
        $this->setHeader('location', $url);
    }

    public function shouldForward() : bool
    {
        return $this->forward !== null;
    }

    public function forwardUrl() : string
    {
        $f = $this->forward;
        if($f === null) {
            return '/';
        }
        return $f;
    }

    public function getCode() : int
    {
         return $this->code;
    }

    public function forbidden() : void
    {
        $this->setCode(403);
        $this->setBody(\NotAuthorized::render());
    }
}
