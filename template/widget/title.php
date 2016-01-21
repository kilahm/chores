<?hh // strict

class :chores:title extends :x:element
{
    use XHPHelpers;

    children (pcdata)*;
    attribute :h1;
    attribute ?string back = null;

    protected function render() : XHPRoot
    {
        $h1 = <h1 class="title">{$this->getChildren()}</h1>;
        $this->transferAllAttributes($h1);

        $back = is_string($this->getAttribute('back')) ?
            <a id="back" href={$this->getAttribute('back')}>&#x029CF;</a> :
            <x:frag />;

        return
            <x:frag>
                {$back}
                {$h1}
            </x:frag>;
    }
}
