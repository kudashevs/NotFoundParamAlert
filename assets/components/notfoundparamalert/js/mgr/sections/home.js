NotFoundParamAlert.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'notfoundparamalert-panel-home'
            ,renderTo: 'notfoundparamalert-panel-home-div'
        }]
    });
    NotFoundParamAlert.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(NotFoundParamAlert.page.Home, MODx.Component);
Ext.reg('notfoundparamalert-page-home', NotFoundParamAlert.page.Home);