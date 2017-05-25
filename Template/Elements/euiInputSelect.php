<?php
namespace exface\JEasyUiTemplate\Template\Elements;

use exface\Core\Widgets\InputSelect;

/**
 * The InputSelect widget will be rendered into a combobox in jEasyUI.
 *
 * @method InputSelect get_widget()
 *        
 * @author Andrej Kabachnik
 *        
 */
class euiInputSelect extends euiInput
{

    protected function init()
    {
        parent::init();
        $this->setElementType('combobox');
    }

    function generateHtml()
    {
        $widget = $this->getWidget();
        $options = '';
        foreach ($widget->getSelectableOptions() as $value => $text) {
            if (! ($this->getWidget()->getMultiSelect() && count($this->getWidget()->getValues()) > 1)) {
                $selected = strcasecmp($this->getValueWithDefaults(), $value) == 0 ? true : false;
            }
            $options .= '
					<option value="' . $value . '"' . ($selected ? ' selected="selected"' : '') . '>' . $text . '</option>';
        }
        
        $output = '	<select style="height: 100%; width: 100%;" class="easyui-' . $this->getElementType() . '" 
						name="' . $widget->getAttributeAlias() . '"  
						id="' . $this->getId() . '"  
						' . ($widget->isRequired() ? 'required="true" ' : '') . '
						' . ($widget->isDisabled() ? 'disabled="disabled" ' : '') . '
						' . ($this->buildJsDataOptions() ? 'data-options="' . $this->buildJsDataOptions() . '" ' : '') . '>
						' . $options . '
					</select>
					';
        return $this->buildHtmlWrapperDiv($output);
    }

    function generateJs()
    {
        $output = '';
        return $output;
    }

    /**
     * Diese Funktion prueft zunaechst ob das JEasyUi-Element auch vorhanden ist.
     * Wenn
     * ja wird es aufgerufen um den momentanen Wert zurueckzugeben, wenn nein wird die
     * jquery-Funktion .val() verwendet um einen Wert zurueckzugeben. Wird der value-
     * Getter aufgerufen bevor das Element initialisiert ist entsteht sonst ein Fehler.
     *
     * {@inheritdoc}
     *
     * @see \exface\AbstractAjaxTemplate\Template\Elements\AbstractJqueryElement::buildJsValueGetter()
     */
    public function buildJsValueGetter()
    {
        if ($this->getWidget()->getMultiSelect()) {
            $value_getter = <<<JS
return $("#{$this->getId()}").{$this->getElementType()}("getValues").join();
JS;
        } else {
            $value_getter = <<<JS
return $("#{$this->getId()}").{$this->getElementType()}("getValue");
JS;
        }
        
        $output = <<<JS

(function(){
	{$this->getId()}_jquery = $("#{$this->getId()}");
	if ({$this->getId()}_jquery.data("{$this->getElementType()}")) {
		{$value_getter}
	} else {
		return {$this->getId()}_jquery.val();
	}
})()
JS;
        return $output;
    }

    public function buildJsDataOptions()
    {
        return "panelHeight: 'auto'" . ($this->getWidget()->getMultiSelect() ? ", multiple:true" : '') . ($this->getWidget()->getMultiSelect() && count($this->getWidget()->getValues()) > 1 ? ", value:['" . implode("'" . $this->getWidget()->getMultiSelectValueDelimiter() . "'", $this->getWidget()->getValues()) . "']" : '');
    }
}
?>