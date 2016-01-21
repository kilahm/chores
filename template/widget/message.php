<?hh // strict

class :chores:message extends :x:element
{
    use XHPHelpers;

    children (pcdata)*;

    attribute :div;

    protected function render() : XHPRoot
    {
        $div =
            <div class="message">
                {$this->getChildren()}
            </div>;

        $this->transferAllAttributes($div);
        return $div;
    }
}
