NotFoundParamAlert.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        border: false
        , baseCls: 'modx-formpanel'
        , items: [{
            html: '<h2>' + _('notfoundparamalert') + '</h2>'
            , border: false
            , cls: 'modx-page-header container'
        }, {
            xtype: 'modx-tabs'
            , bodyStyle: 'padding: 10px'
            , defaults: {border: false, autoHeight: true}
            , border: true
            , activeItem: 0
            , hideMode: 'offsets'
            , items: [{
                title: _('notfoundparamalert')
                , items: [{
                    xtype: 'notfoundparamalert-grid-items'
                    , preventRender: true
                }]
            }]
        }]
    });
    NotFoundParamAlert.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(NotFoundParamAlert.panel.Home, MODx.Panel);
Ext.reg('notfoundparamalert-panel-home', NotFoundParamAlert.panel.Home);