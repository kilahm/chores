<?hh // strict

class :chores:submit-button extends :x:element
{
    use XHPHelpers;

    children (pcdata?);

    attribute :button;

    protected function render() : XHPRoot
    {
        $text = $this->getFirstChild();
        $text = $text === null ? 'Submit' : $text;

        $button = <button type="submit">{$text}</button>;
        $this->transferAttributesExcept($button, Set{'type'});

        return $button;
    }
}
