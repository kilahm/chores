<?hh // strict

use kilahm\chores\model\Migration;

class Migrate
{
    public function __construct(private Vector<Migration> $data)
    {
    }

    public function show() : void
    {
        $rows = $this->data->map($row ==> {
            $start = $row['start'];
            $end = $row['end'];

            $startText = $start === null ?
                'Not started' :
                $start->get()->format(DateTime::RFC1036)
            ;

            $durationText = $end === null ?
                ($start === null ? 'N/A' : 'Incomplete') :
                (
                    $start === null ?
                    'End time with no start time!' : (
                        $end->get()->diff($start->get())->format('%a:%h:%i:%s')
                    )
                )
            ;

            return
                <tr>
                    <td>{$row['signature']}</td>
                    <td>{$startText}</td>
                    <td>{$durationText}</td>
                    <td>{$row['description']}</td>
                </tr>
            ;
        });
        echo
            <chores:root>
            <bootstrap:container>
                <h1 style="text-align: center;">Db Migrations</h1>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Started</th>
                            <th>Duration</th>
                            <th>Description</th>
                        </tr>
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
