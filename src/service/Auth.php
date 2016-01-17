<?hh // strict

namespace kilahm\chores\service;

use kilahm\chores\model\AclStore;
use kilahm\IOC\FactoryContainer;

final class Auth
{
    <<provides('Auth')>>
    public static function factory(FactoryContainer $c) : this
    {
        return new static($c->getSession(), $c->getAclStore());
    }

    public function __construct(
        private Session $session,
        private AclStore $aclStore,
    )
    {
    }

    public function check(AuthGroup $group) : bool
    {
        $user = $this->session->currentUser();
        if($user === null) {
            return false;
        }

        $groups = $this->aclStore->groupsFromUser($user);
        return $groups->contains($group);
    }

    public function isUser() : bool
    {
         return $this->session->currentUser() !== null;
    }
}
