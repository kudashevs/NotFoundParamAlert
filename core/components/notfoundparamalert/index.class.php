<?php
/**
 * Class NotFoundParamAlertMainController
 */
abstract class NotFoundParamAlertMainController extends modExtraManagerController
{
    /** @var NotFoundParamAlert $notfoundparamalert */
    public $notfoundparamalert;

    /**
     * @return void
     */
    public function initialize()
    {
        $corePath = $this->modx->getOption('notfoundparamalert_core_path', null, $this->modx->getOption('core_path') . 'components/notfoundparamalert/');
        require_once $corePath . 'model/notfoundparamalert/notfoundparamalert.class.php';

        $this->notfoundparamalert = new NotFoundParamAlert($this->modx);

        $this->addCss($this->notfoundparamalert->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->notfoundparamalert->config['jsUrl'] . 'mgr/notfoundparamalert.js');
        $this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			NotFoundParamAlert.config = ' . $this->modx->toJSON($this->notfoundparamalert->config) . ';
			NotFoundParamAlert.config.connector_url = "' . $this->notfoundparamalert->config['connectorUrl'] . '";
		});
		</script>');
        parent::initialize();
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('notfoundparamalert:default');
    }

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }
}

/**
 * Class IndexManagerController
 */
class IndexManagerController extends NotFoundParamAlertMainController
{
    /**
     * @return string
     */
    public static function getDefaultController()
    {
        return 'home';
    }
}