<?hh // strict

class :chores:list-item extends :x:element
{
    use XHPHelpers;

    children (pcdata)*;
    attribute :li;
    attribute int count = 0;

    protected function render() : XHPRoot
    {
        $count = (int)$this->getAttribute('count') > 0 ?
            <span class="badge">{$this->getAttribute('count')}</span> :
            <x:frag />;

        $item =
            <li>
                {$count}
                <span class="main-content">{$this->getChildren()}</span>
            </li>;

        $this->transferAllAttributes($item);
        return $item;
    }
}
