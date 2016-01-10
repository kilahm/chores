<?hh // strict

type MigrationData = shape(
    'signature' => string,
    'description' => string,
    'time ran' => ?DateTime,
);

class Migrate
{
    private Vector<MigrationData> $data = Vector{};

    public function registerMigration(MigrationData $data) : void
    {
        $this->data->add($data);
    }

    public function show() : void
    {
        $rows = $this->data->map($row ==> {
            $dt = $row['time ran'];
            return
                <tr>
                    <td>{$row['signature']}</td>
                    <td>{$dt === null ? 'Not applied yet' : $dt->format(DateTime::RFC1036)}</td>
                    <td>{$row['description']}</td>
                </tr>
            ;
        });
        echo
            <chores:root>
            <bootstrap:container>
                <table class="table table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Time applied</th>
                        <th>Description</th>
                    </thead>
                    <tbody>
                        {$rows}
                    </tbody>
                </table>
            </bootstrap:container>
            </chores:root>
        ;
    }
}
