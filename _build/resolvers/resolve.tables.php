<?php

if ($object->xpdo) {
    /* @var modX $modx */
    $modx =& $object->xpdo;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('notfoundparamalert_core_path', null, $modx->getOption('core_path') . 'components/notfoundparamalert/') . 'model/';
            $modx->addPackage('notfoundparamalert', $modelPath);
            $manager = $modx->getManager();
            $objects = array(
                'NotFoundParameter',
            );
            foreach ($objects as $tmp) {
                $manager->createObjectContainer($tmp);
            }
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;