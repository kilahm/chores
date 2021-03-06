<?hh // strict

class :chores:list-item extends :x:element
{
    use XHPHelpers;

    children (pcdata)*;
    attribute :li;
    attribute int count = 0;
    attribute ?string href = null;

    protected function render() : XHPRoot
    {
        $count = (int)$this->getAttribute('count') > 0 ?
            <span class="badge">{$this->getAttribute('count')}</span> :
            <x:frag />;

        $href = $this->getAttribute('href');

        $body =
            <span class="list-content">
                <span class="list-text">
                    {$this->getChildren()}
                </span>
                {$count}
            </span>;

        if($href !== null) {
            $body = <a href={$href}>{$body}</a>;
        }

        $item = <li>{$body}</li>;

        $this->transferAllAttributes($item);
        return $item;
    }
}
