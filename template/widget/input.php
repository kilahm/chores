<?hh // strict

class :chores:input extends :x:element
{
    use XHPHelpers;

    children empty;

    attribute :input;
    attribute bool error = false;
    attribute string label = '';

    protected function render() : XHPRoot
    {
        $id = $this->getID();

        $labelText = $this->getAttribute('label');
        $this->removeAttribute('label');

        $hasLabel = is_string($labelText) && $labelText !== '';
        $this->conditionClass($this->getAttribute('error'), 'error');
        $this->conditionClass($hasLabel, 'labeled');

        $label = $hasLabel ?
            <label class="input" for={$id}>{$labelText}</label> :
            <x:frag />;

        $input = <input />;
        $this->transferAllAttributes($input);

        return
            <div class="form-group">
                {$label}
                {$input}
            </div>;
    }
}
