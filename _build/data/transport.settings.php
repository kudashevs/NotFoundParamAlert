<?php
$settings = array();
$tmp = array(
    'parameters' => array(
        'xtype' => 'textfield',
        'value' => '',
        'area' => PKG_NAME_LOWER . '.main',
    ),
    'parameters_all' => array(
        'xtype' => 'combo-boolean',
        'value' => true,
        'area' => PKG_NAME_LOWER . '.main',
    ),
    'alert_name' => array(
        'xtype' => 'textfield',
        'value' => 'NotFoundParamAlert:',
        'area' => PKG_NAME_LOWER . '.main',
    ),
    'alert_method' => array(
        'xtype' => 'textfield',
        'value' => 'log',
        'area' => PKG_NAME_LOWER . '.main',
    ),
    'alert_log_level' => array(
        'xtype' => 'textfield',
        'value' => 'error',
        'area' => PKG_NAME_LOWER . '.main',
    ),
    'mail_method' => array(
        'xtype' => 'textfield',
        'value' => 'php',
        'area' => PKG_NAME_LOWER . '.main',
    ),
    'mail_from' => array(
        'xtype' => 'textfield',
        'value' => '',
        'area' => PKG_NAME_LOWER . '.main',
    ),
    'mail_to' => array(
        'xtype' => 'textfield',
        'value' => '',
        'area' => PKG_NAME_LOWER . '.main',
    ),
);

foreach ($tmp as $k => $v) {
    /* @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => PKG_NAME_LOWER . '.' . $k,
            'namespace' => PKG_NAME_LOWER,
        ), $v
    ), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;