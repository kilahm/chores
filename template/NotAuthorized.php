<?hh // strict

class NotAuthorized
{
    public static function render() : string
    {
        return (string)
            <html>
            <head></head>
            <body>You are not authorized to view this page.</body>
            </html>;
    }
}
