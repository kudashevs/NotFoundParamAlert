<?php
/**
 * Get a list of Items
 */

class NotFoundParamAlertItemsGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'NotFoundParameter';
    public $classKey = 'NotFoundParameter';
    public $languageTopics = array('notfoundparamalert:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $renderers = '';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['ip_address'] = inet_ntop($array['ip_address']);

        return $array;
    }

}

return 'NotFoundParamAlertItemsGetListProcessor';