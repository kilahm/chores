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

        $label = $this->getAttribute('label');
        $this->removeAttribute('label');

        $hasLabel = is_string($label) && $label !== '';

        $this->conditionClass($this->getAttribute('error'), 'error');
        $this->conditionClass($hasLabel, 'labeled');

        $id = $this->getID();

        $input = <input />;
        $this->transferAllAttributes($input);

        if($hasLabel) {
            return
                <x:frag>
                    <label class="input" for={$id}>{$label}</label>
                    {$input}
                </x:frag>;
        }

        return $input;
    }
}
