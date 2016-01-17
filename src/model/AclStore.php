<?hh // strict

namespace kilahm\chores\model;

use kilahm\chores\service\Db;
use kilahm\chores\service\AuthGroup;
use kilahm\IOC\FactoryContainer;

final class AclStore
{
    const string SCHEMA = <<<SQL
CREATE TABLE IF NOT EXISTS "acl"
(
    "userId" INTEGER REFERENCES "user" ("id"),
    "group" STRING,
    PRIMARY KEY ("userId", "group") ON CONFLICT REPLACE
)
SQL;

    <<provides('AclStore')>>
    public static function factory(FactoryContainer $c) : this
    {
        return new static($c->getDb());
    }

    public function __construct(private Db $db)
    {
    }

    public function groupsFromUser(User $user) : Set<AuthGroup>
    {
        $rawGroups = $this->db
            ->query('SELECT "group" FROM "acl" WHERE "userId" = :userId')
            ->all(Map{':userId' => field\IntField::toStore($user['id'])})
            ;
        $out = Set{};
        foreach($rawGroups as $rawGroup) {
            $group = AuthGroup::coerce($rawGroup);
            if($group !== null) {
                $out->add($group);
            }
        }
        return $out;
    }

    public function authorizeUser(User $user, AuthGroup $group) : void
    {
        $this->db->query('INSERT INTO "acl" ("userId", "group") VALUES (:userId, :group)')
            ->execute(Map{
                ':userId' => field\IntField::toStore($user['id']),
                ':group' => $group,
            })
        ;
    }

    public function deauthorizeUser(User $user, AuthGroup $group) : void
    {
        $this->db->query('DELETE FROM "acl" WHERE "userId" = :userId AND "group" = :group')
            ->execute(Map{
                ':userId' => field\IntField::toStore($user['id']),
                ':group' => $group,
            })
        ;
    }
}
