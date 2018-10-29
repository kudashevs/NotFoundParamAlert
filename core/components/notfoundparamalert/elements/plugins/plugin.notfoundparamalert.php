<?php
/**
 * MODx Revolution plugin generate alert on page not found with specified parameters in URL
 *
 * @package notfoundparamalert
 * @var modX $modx
 */
if ($modx->event->name === 'OnPageNotFound') {
    /**
     * @return array Contains rules matched parameters
     */
    if (!function_exists('getWildcardParams')) {
        function getWildcardParams(array $inputs, array $params)
        {
            $output = [];
            foreach ($inputs as $input) {
                if (strpos($input, '*') !== false || strpos($input, '?') !== false) {
                    foreach ($params as $pk => $pv) {
                        if (fnmatch($input, $pk)) {
                            $output[] = $pk;
                        }
                    }
                } else {
                    if (array_key_exists($input, $params)) {
                        $output[] = $input;
                    }
                }
            }

            return $output;
        }
    }

    /**
     * @return string Captured IP address of localhost
     */
    if (!function_exists('getRemote')) {
        function getRemote()
        {
            global $modx;

            $remotes = $modx->request->getClientIp();
            if (count($remotes['suspected']) > 1) {
                $ip = $remotes['suspected'][0];
            } else {
                $ip = $remotes['ip'];
            }

            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                return '127.0.0.1';
            }

            return $ip;
        }
    }

    $requestMethod = 'GET';
    $inputParams = array_map('trim', explode(',', $modx->getOption('notfoundparamalert.parameters')));
    $requestParams = $modx->request->getParameters([], $requestMethod);
    $checkParams = getWildcardParams($inputParams, $requestParams);

    if (empty($inputParams) || empty($requestParams) || empty($checkParams)) {
        return '';
    }

    $alertName = trim($modx->getOption('notfoundparamalert.alert_name'));
    $alertName = !empty($alertName) ? $alertName : 'NotFoundParamAlert:';
    $alertMethod = array_map('trim', explode(',', strtolower($modx->getOption('notfoundparamalert.alert_method'))));
    $alertMethodAllowed = ['db', 'mail', 'log'];
    $alertLevel = strtoupper($modx->getOption('notfoundparamalert.alert_log_level'));
    $alertLevelAllowed = [1 => 'ERROR', 2 => 'WARN', 3 => 'INFO', 4 => 'DEBUG']; // no FATAL because init site temporary unavailable and 500 header
    $paramsAll = $modx->request->getParameters([], $requestMethod);
    $paramsAll = implode('&', array_map(function($k, $v) { return $k . '=' . $v; }, array_keys($paramsAll), $paramsAll));
    $paramsFound = $modx->request->getParameters($checkParams, $requestMethod);
    $paramsFound = implode('&', array_map(function($k, $v) { return $k . '=' . $v; }, array_keys($paramsFound), $paramsFound));
    $siteUrl = $modx->getOption('site_url');
    $urlParse = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '';
    $urlPath = parse_url($urlParse, PHP_URL_PATH);
    $urlFull = $siteUrl . ltrim(parse_url($urlParse, PHP_URL_PATH), '/'); // no need to sanitize it MODX do it for us

    if (empty($alertMethod) || empty($alertLevel) || empty($paramsAll) || empty($paramsFound) || empty($urlPath)) {
        return '';
    }

    if (array_diff($alertMethod, $alertMethodAllowed)) {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $alertName . ' wrong alert_method value. Check configuration.');
        return '';
    }

    if (!in_array($alertLevel, $alertLevelAllowed)) {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $alertName . ' wrong alert_log_level value. Check configuration.');
        return '';
    }

    $logConst = array_flip($alertLevelAllowed);
    $logLevel = $logConst[$alertLevel];
    $logParams = $modx->getOption('notfoundparamalert.parameters_all') ? $paramsAll : $paramsFound;
    $modx->lexicon->load('notfoundparamalert:messages');

    if (in_array('db', $alertMethod)) {
        $modx->addPackage('notfoundparamalert', MODX_CORE_PATH . 'components/notfoundparamalert/model/');

        $parameter = $modx->newObject('NotFoundParameter', [
            'url_full' => $urlFull,
            'parameters_all' => $paramsAll,
            'parameters_found' => $paramsFound,
            'parameters_pattern' => trim($modx->getOption('notfoundparamalert.parameters')),
            'ip_address' => inet_pton(getRemote())
        ]);

        if (!$parameter->save()) {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $alertName . ' error while storing request in database. You can find captured data on next string.');
            $modx->log($logLevel, $modx->lexicon('log_message', [
                'alertName' => $alertName,
                'alertMethod' => 'log',
                'siteName' => $modx->config['site_name'],
                'siteUrl' => $siteUrl,
                'urlPath' => $urlPath,
                'urlFull' => $urlFull,
                'requestParams' => $logParams,
                'ipAddress' => getRemote(),
            ]));
        }
    }

    if (in_array('mail', $alertMethod)) {

        $mailMethod = strtolower($modx->getOption('notfoundparamalert.mail_method'));
        $mailType = ('modx' === $mailMethod) ? 'mail with MODX Mailer' : 'mail with PHP mail()';
        $mailTo = ($modx->getOption('notfoundparamalert.mail_to')) ? $modx->getOption('notfoundparamalert.mail_to') : $modx->getOption('emailsender');
        $mailFrom = ($modx->getOption('notfoundparamalert.mail_from')) ? $modx->getOption('notfoundparamalert.mail_from') : 'robot@' . preg_replace('/^www\./', '', parse_url($modx->getOption('site_url'), PHP_URL_HOST));
        $mailName = trim($alertName, ' :[]');
        $mailSubj = $modx->lexicon('email_subject', ['alertName' => $alertName]);
        $mailBody = $modx->lexicon('email_body', [
            'alertName' => $alertName,
            'alertMethod' => $mailType,
            'siteName' => $modx->config['site_name'],
            'siteUrl' => $siteUrl,
            'urlPath' => $urlPath,
            'urlFull' => $urlFull,
            'requestParams' => $logParams,
            'ipAddress' => getRemote(),
        ]);

        if ('modx' === $mailMethod) {
            /** @var modPHPMailer $mail */
            $mail = $modx->getService('mail', 'mail.modPHPMailer');
            $mail->setHTML(true);
            $mail->set(modMail::MAIL_SUBJECT, $mailSubj);
            $mail->set(modMail::MAIL_BODY, $mailBody);
            $mail->set(modMail::MAIL_SENDER, $mailFrom);
            $mail->set(modMail::MAIL_FROM, $mailFrom);
            $mail->set(modMail::MAIL_FROM_NAME, $mailName);
            $mail->address('to', $mailTo);
            $mail->address('reply-to', $mailFrom);
            if (!$mail->send()) {
                $modx->log(xPDO::LOG_LEVEL_ERROR, $alertName . ' error while sending email with ' . $mailMethod. ' error '. $modx->mail->mailer->ErrorInfo . '. You can find captured data on next string.');
                $modx->log($logLevel, $modx->lexicon('log_message', [
                    'alertName' => $alertName,
                    'alertMethod' => $mailType,
                    'siteName' => $modx->config['site_name'],
                    'siteUrl' => $siteUrl,
                    'urlPath' => $urlPath,
                    'urlFull' => $urlFull,
                    'requestParams' => $logParams,
                    'ipAddress' => getRemote(),
                ]));
            }
            $mail->reset();
        } else {
            $mailSubj = '=?UTF-8?B?' . base64_encode($mailSubj) . '?=';
            $headers = 'From: ' . $mailName .' <' . $mailFrom . '>' . PHP_EOL;
            $headers .= 'Reply-To: ' . $mailFrom . '' . PHP_EOL;
            $headers .= 'Content-Type: text/html; charset=UTF-8' . PHP_EOL;
            $headers .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL;
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $status = mail($mailTo, $mailSubj, $mailBody, $headers);
            if (!$status) {
                $modx->log(xPDO::LOG_LEVEL_ERROR, $alertName . ' error while sending email with ' . $mailMethod. ' error '. error_get_last()['message'] . '. You can find captured data on next string.');
                $modx->log($logLevel, $modx->lexicon('log_message', [
                    'alertName' => $alertName,
                    'alertMethod' => $mailType,
                    'siteName' => $modx->config['site_name'],
                    'siteUrl' => $siteUrl,
                    'urlPath' => $urlPath,
                    'urlFull' => $urlFull,
                    'requestParams' => $logParams,
                    'ipAddress' => getRemote(),
                ]));
            }
        }
    }

    if (in_array('log', $alertMethod)) {
        $modx->log($logLevel, $modx->lexicon('log_message', [
            'alertName' => $alertName,
            'alertMethod' => 'log',
            'siteName' => $modx->config['site_name'],
            'siteUrl' => $siteUrl,
            'urlPath' => $urlPath,
            'urlFull' => $urlFull,
            'requestParams' => $logParams,
            'ipAddress' => getRemote(),
        ]));
    }

    unset($inputParams, $requestParams, $checkParams, $paramsAll, $paramsFound, $logParams, $urlParse, $urlPath, $urlFull);
}