<?hh // strict

class Error
{
    public static function show(\Exception $e) : void
    {
        echo
            <html>
                <head>
                </head>
                <body>
                    <h1>Uncaught Exception</h1>
                    <h2>{get_class($e)}:{$e->getFile()}({$e->getLine()})</h2>
                    <p>{$e->getMessage()}</p>
                    <pre>{$e->getTraceAsString()}</pre>
                </body>
            </html>
        ;
    }
}
