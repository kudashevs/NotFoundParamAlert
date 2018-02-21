<?php
/**
 * MODx Revolution plugin generate alert on page not found with specified parameters in URL
 *
 * @package notfoundparamalert
 * @var modX $modx
 */
if ($modx->event->name === 'OnPageNotFound') {

    if (!function_exists('getWildParams')) {
        function getWildParams($inputs, $params)
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
                    if(array_key_exists($input, $params)) {
                        $output[] = $input;
                    }
                }
            }
            return $output;
        }
    }

    $requestMethod = 'GET';
    $requestParams = $modx->request->getParameters([], $requestMethod);
    $inputParams = explode(',', $modx->getOption('notfoundparamalert.parameters'));
    $checkParams = getWildParams($inputParams, $requestParams);

    if (empty($requestParams) || empty($inputParams) || empty($checkParams)) {
        return '';
    }
    unset($inputParams, $requestParams);

    $alertName = $modx->getOption('notfoundparamalert.alert_name');
    $alertMethod = strtolower($modx->getOption('notfoundparamalert.alert_method'));
    $alertMethodAllowed = ['mail', 'log', 'both'];
    $alertLevel = strtoupper($modx->getOption('notfoundparamalert.alert_log_level'));
    $alertLevelAllowed = ['1' => 'ERROR', '2' => 'WARN', '3' => 'INFO', '4' => 'DEBUG']; // FATAL init site temporary unavailable and 500 header
    $foundParams = $modx->getOption('notfoundparamalert.parameters_all') ? $modx->request->getParameters([], $requestMethod) : $modx->request->getParameters($checkParams, $requestMethod);
    $urlParse = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '';
    $urlPath = parse_url($urlParse, PHP_URL_PATH);
    $urlFull = $modx->getOption('site_url') . ltrim(parse_url($urlParse, PHP_URL_PATH), '/');

    if (empty($alertMethod) || !in_array($alertMethod, $alertMethodAllowed) ||
        empty($alertLevel) || !in_array($alertLevel, $alertLevelAllowed) ||
        empty($foundParams) || empty($urlPath))
    {
        return '';
    }

    $logConst = array_flip($alertLevelAllowed);
    $logLevel = $logConst[$alertLevel];
    $logParams = implode('&', array_map(function($k, $v) { return $k . '=' . $v; }, array_keys($foundParams), $foundParams));
    $modx->lexicon->load('notfoundparamalert:default');

    if('mail' === $alertMethod || 'both' ===  $alertMethod) {

        $mailMethod = strtolower($modx->getOption('notfoundparamalert.mail_method'));
        $mailTo = ($modx->getOption('notfoundparamalert.mail_to')) ? $modx->getOption('notfoundparamalert.mail_to') : $modx->getOption('emailsender');
        $mailFrom = ($modx->getOption('notfoundparamalert.mail_from')) ? $modx->getOption('notfoundparamalert.mail_from') : 'robot@' . preg_replace('/^www\./', '', parse_url($modx->getOption('site_url'), PHP_URL_HOST));
        $mailName = trim($alertName, ' :[]');
        $mailSubj = $modx->lexicon('email_subject', ['alertName' => $alertName]);
        $mailBody = $modx->lexicon('email_body', [
            'alertName' => $alertName,
            'alertMethod' => $alertMethod,
            'siteName' => $modx->config['site_name'],
            'siteUrl' => $modx->getOption('site_url'),
            'urlPath' => $urlPath,
            'urlFull' => $urlFull,
            'requestParams' => $logParams,
            'ipAddress' => $_SERVER['REMOTE_ADDR'],
        ]);

        if('modx' === $mailMethod) {
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
                $modx->log(xPDO::LOG_LEVEL_ERROR, $alertName . ' ERROR while sending email with ' . $mailMethod. ' error '. $modx->mail->mailer->ErrorInfo . '. Catched info on next string');
                $modx->log($logLevel, $modx->lexicon('log_message', [
                    'alertName' => $alertName,
                    'alertMethod' => $alertMethod,
                    'siteName' => $modx->config['site_name'],
                    'siteUrl' => $modx->getOption('site_url'),
                    'urlPath' => $urlPath,
                    'urlFull' => $urlFull,
                    'requestParams' => $logParams,
                    'ipAddress' => $_SERVER['REMOTE_ADDR'],
                ]));
            }
            $mail->reset();
        } else {
            $headers = 'From: ' . $mailName .' <' . $mailFrom . '>' . PHP_EOL;
            $headers .= 'Reply-To: ' . $mailFrom . '' . PHP_EOL;
            $headers .= 'Content-Type: text/html; charset=UTF-8' . PHP_EOL;
            $headers .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL;
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $status = mail($mailTo, $mailSubj, $mailBody, $headers);
            if (!$status) {
                $modx->log(xPDO::LOG_LEVEL_ERROR, $alertName . ' ERROR while sending email with ' . $mailMethod. ' error '. error_get_last()['message'] . '. Catched info on next string');
                $modx->log($logLevel, $modx->lexicon('log_message', [
                    'alertName' => $alertName,
                    'alertMethod' => $alertMethod,
                    'siteName' => $modx->config['site_name'],
                    'siteUrl' => $modx->getOption('site_url'),
                    'urlPath' => $urlPath,
                    'urlFull' => $urlFull,
                    'requestParams' => $logParams,
                    'ipAddress' => $_SERVER['REMOTE_ADDR'],
                ]));
            }
        }
    }

    if('log' === $alertMethod || 'both' ===  $alertMethod) {
        $modx->log($logLevel, $modx->lexicon('log_message', [
            'alertName' => $alertName,
            'alertMethod' => $alertMethod,
            'siteName' => $modx->config['site_name'],
            'siteUrl' => $modx->getOption('site_url'),
            'urlPath' => $urlPath,
            'urlFull' => $urlFull,
            'requestParams' => $logParams,
            'ipAddress' => $_SERVER['REMOTE_ADDR'],
        ]));
    }

}