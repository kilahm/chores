<?hh // strict

class :chores:title extends :x:element
{
    use XHPHelpers;

    children (pcdata);
    attribute :h1;

    protected function render() : XHPRoot
    {
        $h1 = <h1 class="title">{$this->getChildren()}</h1>;
        $this->transferAllAttributes($h1);
        return $h1;
    }
}
