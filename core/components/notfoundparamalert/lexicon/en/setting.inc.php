<?php
$_lang['area_notfoundparamalert.main'] = 'Main';

$_lang['setting_notfoundparamalert.parameters'] = 'URL parameters';
$_lang['setting_notfoundparamalert.parameters_desc'] = 'List of URL parameters, separated by coma, which will generate alert. Feel free to use simple wildcards from <a href="http://php.net/manual/en/function.fnmatch.php" target="_blank">fnmatch</a>.';
$_lang['setting_notfoundparamalert.parameters_all'] = 'Include all parameters';
$_lang['setting_notfoundparamalert.parameters_all_desc'] = 'Include in alert message all URL parameters or only matching parameters.';
$_lang['setting_notfoundparamalert.alert_name'] = 'Component name';
$_lang['setting_notfoundparamalert.alert_name_desc'] = 'Component name that will be displayed in generated alert messages.';
$_lang['setting_notfoundparamalert.alert_method'] = 'Alert method';
$_lang['setting_notfoundparamalert.alert_method_desc'] = 'Alert method to deliver generated alert. Available values: "mail", "log".';
$_lang['setting_notfoundparamalert.alert_log_level'] = 'Alert log level';
$_lang['setting_notfoundparamalert.alert_log_level_desc'] = 'Alert log level when logging alert message. Available values: "error", "warn", "info", "debug".';
$_lang['setting_notfoundparamalert.mail_method'] = 'Send mail method';
$_lang['setting_notfoundparamalert.mail_method_desc'] = 'Send mail by native php <a href="http://php.net/manual/en/function.mail.php" target="_blank">mail</a> function or use MODX mailer. Available values: "php", "modx".';
$_lang['setting_notfoundparamalert.mail_from'] = 'Mail from';
$_lang['setting_notfoundparamalert.mail_from_desc'] = 'Insert valid email from which alert messages will be send. If empty will automatically generate address: robot@site_url';
$_lang['setting_notfoundparamalert.mail_to'] = 'Mail to';
$_lang['setting_notfoundparamalert.mail_to_desc'] = 'Insert valid email where alert messages will be send.';