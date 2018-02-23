<?php
$_lang['area_notfoundparamalert.main'] = 'Основные';

$_lang['setting_notfoundparamalert.parameters'] = 'URL параметры';
$_lang['setting_notfoundparamalert.parameters_desc'] = 'Список параметров URL через запятую, которые будут вызывать уведомление. Можно использовать простые подстановки из <a href="http://php.net/manual/ru/function.fnmatch.php" target="_blank">fnmatch</a>.';
$_lang['setting_notfoundparamalert.parameters_all'] = 'Учитывать все параметры';
$_lang['setting_notfoundparamalert.parameters_all_desc'] = 'Включать в уведомление все URL параметры или только совпавшие с искомыми.';
$_lang['setting_notfoundparamalert.alert_name'] = 'Название компонента';
$_lang['setting_notfoundparamalert.alert_name_desc'] = 'Название компонента которое будет отображаться в генерируемых уведомлениях.';
$_lang['setting_notfoundparamalert.alert_method'] = 'Способ уведомления';
$_lang['setting_notfoundparamalert.alert_method_desc'] = 'Способ доставки сгенерированного уведомления. Доступные значения: "mail", "log".';
$_lang['setting_notfoundparamalert.alert_log_level'] = 'Уровень логирования';
$_lang['setting_notfoundparamalert.alert_log_level_desc'] = 'Уровень логирования при записи сообщений в журнал ошибок. Доступные значения: "error", "warn", "info", "debug".';
$_lang['setting_notfoundparamalert.mail_method'] = 'Способ отправки почты';
$_lang['setting_notfoundparamalert.mail_method_desc'] = 'Отправка почты встроенной в php функцией <a href="http://php.net/manual/ru/function.mail.php" target="_blank">mail</a> или использовать MODX мейлер. Доступные значения: "php", "modx".';
$_lang['setting_notfoundparamalert.mail_from'] = 'От кого';
$_lang['setting_notfoundparamalert.mail_from_desc'] = 'Введите валидный email адрес с которого будут отправляться сообщения. Если не заполнено по умолчанию сгенерирует: robot@site_url';
$_lang['setting_notfoundparamalert.mail_to'] = 'Кому';
$_lang['setting_notfoundparamalert.mail_to_desc'] = 'Введите валидный email адрес кому будут отправляться сообщения.';