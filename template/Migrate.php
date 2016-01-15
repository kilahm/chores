<?hh // strict

use kilahm\chores\model\Migration;

class Migrate
{
    public function __construct(
        private Vector<Migration> $all,
        private Vector<Migration> $thisRun = Vector{},
    )
    {
    }

    public function render() : string
    {
        $thisRunSignatures = $this->thisRun->map($m ==> $m['signature'])->toSet();

        $rows = $this->all->map($row ==> {
            $start = $row['start'];
            $end = $row['end'];

            $startText = $start === null ?
                'Not started' :
                $start->format(DateTime::RFC1036)
            ;

            $durationText = $end === null ?
                ($start === null ? 'N/A' : 'Incomplete') :
                (
                    $start === null ?
                    'End time with no start time!' : (
                        $end->diff($start)->format('%a:%h:%i:%s')
                    )
                )
            ;

            $class = $thisRunSignatures->contains($row['signature']) ?
                'bg-success' :
                '';
            return
                <tr class={$class}>
                    <td>{$row['signature']}</td>
                    <td>{$startText}</td>
                    <td>{$durationText}</td>
                    <td>{$row['description']}</td>
                </tr>
            ;
        });

        return (string)
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
                <form method="post">
                <button class="btn btn-default btn-block" formaction="/migrate">Run Migrations</button>
                </form>
            </bootstrap:container>
            </chores:root>
        ;
    }
}
