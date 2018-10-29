<?php
/**
 * The home manager controller for NotFoundParamAlert.
 *
 */
class NotFoundParamAlertHomeManagerController extends NotFoundParamAlertMainController
{
    /* @var NotFoundParamAlert $notfoundparamalert */
    public $notfoundparamalert;


    /**
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = array())
    {
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('notfoundparamalert');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addJavascript($this->notfoundparamalert->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->notfoundparamalert->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->notfoundparamalert->config['jsUrl'] . 'mgr/sections/home.js');
        $this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "notfoundparamalert-page-home"});
		});
		</script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->notfoundparamalert->config['templatesPath'] . 'home.tpl';
    }
}