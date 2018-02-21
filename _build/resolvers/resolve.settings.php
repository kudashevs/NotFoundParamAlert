<?php
/**
 * Resolves setup-options settings
 *
 * @var xPDOObject $object
 * @var array $options
 */

if ($object->xpdo) {
    /** @var modX $modx */
    $modx =& $object->xpdo;

    $success = false;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            /** @var modSystemSetting $old_setting */
            if (($old_setting = $modx->getObject('modSystemSetting', 'notfoundparamalert.email_to')) && ($new_setting = $modx->getObject('modSystemSetting', 'notfoundparamalert.mail_to'))) {
                $old_value = $old_setting->get('value');
                $new_setting->set('value', $old_value);
                if($new_setting->save()) {
                    if(false === $old_setting->remove()) {
                        echo 'Cannot remove old setting';
                    }
                }
            }
            $success = true;
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            $success = true;
            break;
    }

    return $success;
}
