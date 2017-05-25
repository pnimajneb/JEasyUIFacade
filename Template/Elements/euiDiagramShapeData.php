<?php

namespace exface\JEasyUiTemplate\Template\Elements;

use exface\Core\Interfaces\Actions\ActionInterface;

class euiDiagramShapeData extends euiAbstractElement
{

    function generateHtml()
    {
        return '';
    }

    function generateJs()
    {
        return '';
    }

    public function buildJsDataGetter(ActionInterface $action = null)
    {
        if ($action) {
            $rows = "[{'" . $this->getMetaObject()->getUidAlias() . "': " . $this->buildJsValueGetter() . "}]";
        } else {
            // TODO
        }
        return "{oId: '" . $this->getWidget()->getMetaObjectId() . "', rows: " . $rows . "}";
    }

    public function buildJsValueGetter()
    {
        $js = $this->getTemplate()
            ->getElement($this->getWidget()
            ->getDiagram())
            ->getId() . "_selected.data('oid')";
        return $js;
    }

    public function buildJsRefresh()
    {
        return $this->getTemplate()
            ->getElement($this->getWidget()
            ->getDiagram())
            ->buildJsRefresh();
    }
}
?>