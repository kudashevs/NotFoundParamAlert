<?php

/**
 * Remove an Item
 */
class NotFoundParamAlertItemRemoveProcessor extends modObjectRemoveProcessor
{
    public $checkRemovePermission = true;
    public $objectType = 'NotFoundParameter';
    public $classKey = 'NotFoundParameter';
    public $languageTopics = array('notfoundparamalert:default');

}

return 'NotFoundParamAlertItemRemoveProcessor';