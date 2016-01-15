<?hh // strict

namespace kilahm\chores\service;

use kilahm\chores\model\SessionStore;
use kilahm\chores\model\Session as SessionModel;
use kilahm\chores\model\UserStore;
use kilahm\chores\model\User as UserModel;
use kilahm\IOC\FactoryContainer;

<<__ConsistentConstruct>>
class Session
{
    <<provides('Session')>>
    public static function factory(FactoryContainer $c) : this
    {
        $config = $c->getConfig();
        $req = $c->getRequest();

        return new static(
            $req->cookie($config->sessionCookieName()),
            $c->getSessionStore(),
            $c->getUserStore(),
            $c->getResponse(),
            $config->sessionCookieName(),
        );
    }

    private ?SessionModel $currentSession = null;

    private bool $alreadyChecked = false;

    public function __construct(
        private ?string $key,
        private SessionStore $sessionStore,
        private UserStore $userStore,
        private Response $rsp,
        private string $cookieName,
    )
    {
    }

    /**
     * Look in the database for the current session based on a cookie
     */
    public function fetchCurrentSession() : ?SessionModel
    {
        $this->alreadyChecked = true;

        // Fetch from the DB and store in memory
        $currentSession = $this->sessionStore->fetchWithKey($this->key);
        $this->currentSession = $currentSession;

        if($currentSession === null) {
            return null;
        }

        // Expire old sessions
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        if($currentSession['ttl'] < $now) {
            $this->currentSession = null;
            return null;
        }

        return $currentSession;
    }

    /**
     * Give the current session, looking in the DB once only
     */
    public function currentSession() : ?SessionModel
    {
        // Return the in-memory copy if available
        $current = $this->currentSession;
        if($current !== null) {
            return $current;
        }

        // Only check once
        if($this->alreadyChecked) {
             return null;
        }

        // Hit the DB and update the in memory reference
        return $this->fetchCurrentSession();
    }

    /**
     * Create a new session for the given user
     */
    public function newSession(UserModel $user) : void
    {
        $session = shape(
            'userId' => $user['id'],
            'key' => $this->makeSessionKey(),
            'ttl' => new \DateTime('now + 24 hours', new \DateTimeZone('UTC')),
        );

        $this->sessionStore->save($session);

        $this->rsp->setCookie($this->cookieName, $session['key'], $session['ttl']);
    }

    private function makeSessionKey() : string
    {
        $attempts = 0;
        while(true) {
            $key = password_hash(bin2hex(openssl_random_pseudo_bytes(20)), PASSWORD_DEFAULT);
            if(is_string($key)) {
                return $key;
            }
            $attempts += 1;
            if($attempts > 1000) {
                 throw new \Exception('Unable to create a new session key');
            }
        }
    }
}
