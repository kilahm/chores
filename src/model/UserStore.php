<?hh // strict

namespace kilahm\chores\model;

type User = shape(
    'id' => field\IntField,
    'publicId' => field\StringField,
    'name' => field\StringField,
    'tempPassword' => field\BoolField,
);

<<__ConsistentConstruct>>
class UserStore
{
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

    private function fromData(Map<string,string> $data) : User
    {
        return shape(
            'id' => field\IntField::buildFromStore('id'),
            'publicId' => field\StringField::buildFromStore('publicId'),
            'name' => field\StringField::buildFromStore('name'),
            'tempPassword' => field\BoolField::buildFromStore('tempPassword'),
        );
    }

    public function newUser() : User
    {
        return shape(
            'id' => new field\IntField(-1),
            'publicId' => new field\StringField(''),
            'name' => new field\StringField(''),
            'tempPassword' => new field\BoolField(true),
        );
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
