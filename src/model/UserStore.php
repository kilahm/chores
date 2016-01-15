<?hh // strict

namespace kilahm\chores\model;

type User = shape(
    'id' => int,
    'publicId' => string,
    'name' => string,
    'tempPassword' => bool,
);

<<__ConsistentConstruct>>
class UserStore
{
    const string SCHEMA = <<<SQL
CREATE TABLE IF NOT EXISTS "user" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "publicId" STRING,
    "name" STRING,
    "tempPassword" INT,
    "password" STRING
);
SQL;

    private static string $garbage = 'garbage';
    // hash of 'garbage'
    private static string $noise = '$2y$10$t.4wpJAVK6lj3PnJrZig0uQ37fB2PCU6uxApVmz/3RuCdDx.8htd6';
    private static int $passwordCost = 10;

    <<provides('UserStore')>>
    public static function factory(\kilahm\IOC\FactoryContainer $c) : this
    {
        return new static($c->getDb());
    }

    public function __construct(private \kilahm\chores\service\Db $db)
    {
    }

    public function fromCreds(string $name, string $password) : ?User
    {
        $result = $this->db->query('SELECT * FROM user WHERE name = :name')
            ->one(Map{'name' => $name});

        if($result === null) {
            $this->checkPassword(self::$garbage, self::$noise);
            return null;
        }

        if($this->checkPassword($password, $result->at('password'))) {
            $user = $this->fromData($result);
            $this->rehash($password, $user);
            return $user;
        }

        return null;
    }

    public function newUser(string $name, string $plainPassword) : void
    {
        $hash = $this->makePassword($plainPassword);
        $newData = Map{
            ':name' => $name,
            ':password' => $hash,
            ':tempPassword' => '1',
        };

        $this->db
            ->query('INSERT INTO "user" ("name", "password", "tempPassword") VALUES (:name, :password, :tempPassword)')
            ->execute($newData)
        ;

        $id = $this->db->lastInsertId();
        if($id === null) {
            throw new \Exception('Unable to create a new user');
        }

        $unique = $id . $name;
        $this->db
            ->query('UPDATE "user" SET "publicId" = :publicId WHERE "id" = :id')
            ->execute(Map{
                ':publicId' => \kilahm\chores\Config::makePublicId($unique),
                ':id' => $id,
            })
        ;
    }

    private function fromData(Map<string,string> $data) : User
    {
        return shape(
            'id' => field\IntField::fromStore($data->at('id')),
            'publicId' => $data->at('publicId'),
            'name' => $data->at('name'),
            'tempPassword' => field\BoolField::fromStore($data->at('tempPassword')),
        );
    }

    private function toData(User $user) : Map<string, string>
    {
        return Map{
            'id' => field\IntField::toStore($user['id']),
            'publicId' => $user['publicId'],
            'name' => $user['name'],
            'tempPassword' => field\BoolField::toStore($user['tempPassword']),
        };
    }

    private function checkPassword(string $plain, string $hash) : bool
    {
         return password_verify($plain, $hash);
    }

    private function makePassword(string $plain) : string
    {
        while(true) {
            $hash = password_hash($plain, PASSWORD_DEFAULT, ['cost' => self::$passwordCost]);
            if(is_string($hash)) {
                return $hash;
            }
        }
    }

    private function rehash(string $plain, User $user) : void
    {
        if(password_needs_rehash($plain, PASSWORD_DEFAULT, ['cost' => self::$passwordCost])) {
            $newHash = $this->makePassword($plain);
            $this->db->query('UPDATE user SET password = :password WHERE id = :id')
                ->execute(Map{
                    'password' => $newHash,
                    'id' => (string)$user['id']
                })
            ;
        }
    }
}
