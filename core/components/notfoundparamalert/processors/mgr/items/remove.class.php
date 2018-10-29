<?php

/**
 * Remove an Items
 */
class NotFoundParamAlertItemsRemoveProcessor extends modProcessor
{
    public $checkRemovePermission = true;
    public $objectType = 'NotFoundParameter';
    public $classKey = 'NotFoundParameter';
    public $languageTopics = array('notfoundparamalert:default');

    public function process()
    {
        foreach (explode(',', $this->getProperty('items')) as $id) {
            $item = $this->modx->getObject($this->classKey, $id);
            $item->remove();
        }

        return $this->success();
    }
}

return 'NotFoundParamAlertItemsRemoveProcessor';