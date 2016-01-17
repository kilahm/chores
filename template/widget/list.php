<?hh // strict

class :chores:list extends :x:element
{
    use XHPHelpers;

    children (:chores:list-item+);

    protected function render() : XHPRoot
    {
        $ul = <ul>{$this->getChildren()}</ul>;
        $this->transferAllAttributes($ul);
        return $ul;
    }
}
