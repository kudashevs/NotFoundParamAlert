<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('notfoundparamalert_core_path', null, $modx->getOption('core_path') . 'components/notfoundparamalert/');
require_once $corePath . 'model/notfoundparamalert/notfoundparamalert.class.php';
$modx->notfoundparamalert = new NotFoundParamAlert($modx);

$modx->lexicon->load('notfoundparamalert:default');

/* handle request */
$path = $modx->getOption('processorsPath', $modx->notfoundparamalert->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));