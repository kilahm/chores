<?hh // strict

class :chores:root extends :x:element
{
    protected function render() : XHPRoot
    {
        return
            <html>
                <head>
                    <link
                        rel="stylesheet"
                        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
                        crossorigin="anonymous"
                    />

                    <link
                        rel="stylesheet"
                        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
                        crossorigin="anonymous"
                    />

                </head>
                <body>
                    <bootstrap:root>
                        {$this->getChildren()}
                    </bootstrap:root>
                    <script
                        src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
                        crossorigin="anonymous"
                    />
                </body>
            </html>
        ;
    }
}
