<?php

/**
 * Get an Item
 */
class NotFoundParamAlertItemShowProcessor extends modObjectGetProcessor
{
    public $objectType = 'NotFoundParameter';
    public $classKey = 'NotFoundParameter';
    public $languageTopics = array('notfoundparamalert:default');


    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $this->beforeOutput();
        $array = $this->object->toArray();
        $array['ip_address'] = inet_ntop($array['ip_address']);

        return $this->success('', $array);
    }
}

return 'NotFoundParamAlertItemShowProcessor';