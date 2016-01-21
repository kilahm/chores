<?hh // strict

class :chores:error extends :x:element
{
    use XHPHelpers;

    children (pcdata);

    attribute :div;

    protected function render() : XHPRoot
    {
        $div = <div class="block-error">{$this->getChildren()}</div>;
        $this->transferAllAttributes($div);
        return $div;
    }
}
