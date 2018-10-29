var NotFoundParamAlert = function (config) {
    config = config || {};
    NotFoundParamAlert.superclass.constructor.call(this, config);
};
Ext.extend(NotFoundParamAlert, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}
});
Ext.reg('notfoundparamalert', NotFoundParamAlert);

NotFoundParamAlert = new NotFoundParamAlert();