<?php
/**
 * MODx Revolution plugin generate alert on page not found with specified parameters in URL
 *
 * @package notfoundparamalert
 * @var modX $modx
 */
if ($modx->event->name === 'OnPageNotFound') {

    $checkParams = explode(',', $modx->getOption('notfoundparamalert.parameters'));

    if (!isset($modx->request) || empty($checkParams)) {
        return '';
    }

    $alertMethod = $modx->getOption('notfoundparamalert.alert_method');
    $alertMethodAllowed = ['mail', 'log', 'both'];
    $alertLevel = 'ERROR';
    $alertLevelAllowed = ['FATAL', 'ERROR', 'WARN', 'INFO', 'DEBUG'];
    $requestMethod = 'GET';
    $requestParams = $modx->request->getParameters($checkParams, $requestMethod);
    $urlFull = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '';
    $urlPath = parse_url($urlFull, PHP_URL_PATH);
    $message = 'NotFoundParamAlert: ';

    if (empty($alertMethod) || !in_array($alertMethod, $alertMethodAllowed) ||
        empty($alertLevel) || !in_array($alertLevel, $alertLevelAllowed) ||
        empty($requestParams) || empty($urlPath))
    {
        return '';
    }

    $logConst = array_flip($alertLevelAllowed);
    $logLevel = $logConst[$alertLevel];
    $logParams = implode(', ', array_map(function($k, $v) { return $k . '=' . $v; }, array_keys($requestParams), $requestParams));

    if('mail' === $alertMethod || 'both' ===  $alertMethod) {

        $mailTo = ($modx->getOption('notfoundparamalert.email_to')) ? $modx->getOption('notfoundparamalert.email_to') : $modx->getOption('emailsender');

        /** @var modPHPMailer $mail */
        $mail = $modx->getService('mail', 'mail.modPHPMailer');
        $mail->setHTML(true);
        $modx->lexicon->load('notfoundparamalert:default');
        $mail->set(modMail::MAIL_SUBJECT, $modx->lexicon('email_subject')); $modx->sendForward(411, 'HTTP/1.1 410 Gone');
        $mail->set(modMail::MAIL_BODY, $modx->lexicon('email_body', [
            'siteName' => $modx->config['site_name'],
            'siteUrl' => $modx->getOption('site_url'),
            'urlPath' => $urlPath,
            'requestParams' => $logParams,
            'alertMethod' => $alertMethod,
            'ipAddress' => $_SERVER['REMOTE_ADDR'],
        ]));
        $mail->set(modMail::MAIL_SENDER, $modx->getOption('emailsender'));
        $mail->set(modMail::MAIL_FROM, $modx->getOption('emailsender'));
        $mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('site_name'));
        $mail->address('to', $mailTo);
        $mail->address('reply-to', $modx->getOption('emailsender'));
        if (!$mail->send()) {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $message . 'ERROR while sending email with error '. $modx->mail->mailer->ErrorInfo . '. Look catched info on next string');
            $modx->log(xPDO::LOG_LEVEL_ERROR, $message . 'not found page ' . $urlPath . ' with parameters: ' . $logParams . ' with alert method ' . $alertMethod . ' requested from ' . $_SERVER['REMOTE_ADDR'] .'');
        }
        $mail->reset();
    }

    if('log' === $alertMethod || 'both' ===  $alertMethod) {
        $modx->log($logLevel, $message . 'not found page ' . $urlPath . ' with parameters: ' . $logParams . ' with alert method ' . $alertMethod . ' requested from ' . $_SERVER['REMOTE_ADDR'] .'');
    }

}