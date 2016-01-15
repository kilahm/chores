<?hh // strict

class :chores:top-head extends :x:element
{
    children (pcdata);

    public function render() : XHPRoot
    {
        $style = implode(';', (Map{
            'text-align' => 'center'
        })->mapWithKey(($k, $v) ==> "$k: $v"));
        return
            <bootstrap:jumbotron>
                <h1 style={$style}>
                    {$this->getChildren()}
                </h1>
            </bootstrap:jumbotron>
            ;
    }
}
