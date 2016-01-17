<?hh // strict

class :chores:root extends :x:element
{
    protected function render() : XHPRoot
    {
        $style = implode(';', (Map{
            'font-size' => 'large'
        })->mapWithKey(($k, $v) ==> "$k: $v"));
        return
            <html>
                <head>
                    <link rel="stylesheet" href="/style.css" />
                </head>
                <body>
                    {$this->getChildren()}
                </body>
            </html>
        ;
    }
}
