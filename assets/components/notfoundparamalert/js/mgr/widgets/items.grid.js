NotFoundParamAlert.grid.Items = function (config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();

    Ext.applyIf(config, {
        id: 'notfoundparamalert-grid-items'
        , url: NotFoundParamAlert.config.connector_url
        , baseParams: {
            action: 'mgr/items/getlist'
        }
        , fields: ['id', 'url_full', 'parameters_all', 'parameters_found', 'parameters_pattern', 'ip_address', 'time']
        , autoHeight: true
        , paging: true
        , remoteSort: true
        , sm: this.sm
        , columns: [
            this.sm
            , {header: _('notfoundparamalert_id'), dataIndex: 'id', sortable: true, width: 10}
            , {header: _('notfoundparamalert_url_full'), dataIndex: 'url_full', sortable: true, width: 70}
            , {header: _('notfoundparamalert_parameters_all'), dataIndex: 'parameters_all', sortable: true, width: 70}
            , {header: _('notfoundparamalert_parameters_found'), dataIndex: 'parameters_found', sortable: true, width: 70}
            , {header: _('notfoundparamalert_parameters_pattern'), dataIndex: 'parameters_pattern', sortable: true, width: 70}
            , {header: _('notfoundparamalert_ip_address'), dataIndex: 'ip_address', sortable: true, width: 70}
            , {header: _('notfoundparamalert_timestamp'), dataIndex: 'time', sortable: true, width: 100}
        ]
        , listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.showItem(grid, e, row);
            }
        }
    });
    NotFoundParamAlert.grid.Items.superclass.constructor.call(this, config);
};
Ext.extend(NotFoundParamAlert.grid.Items, MODx.grid.Grid, {
    windows: {}

    , getMenu: function () {
        var cs = this.getSelectedAsList();
        var m = [];
        if (cs.split(',').length > 1) {
            m.push({
                text:  _('notfoundparamalert_items_remove')
                , handler: this.removeSelected
            });
        } else {
            m.push({
                text:  _('notfoundparamalert_item_show')
                , handler: this.showItem
            });
            m.push('-');
            m.push({
                text:  _('notfoundparamalert_item_remove')
                , handler: this.removeItem
            });
        }
        this.addContextMenuItem(m);
    }

    , showItem: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: NotFoundParamAlert.config.connector_url
            , params: {
                action: 'mgr/item/show'
                , id: id
            }
            , listeners: {
                success: {
                    fn: function (r) {
                        if (!this.windows.showItem) {
                            this.windows.showItem = MODx.load({
                                xtype: 'notfoundparamalert-window-item-show'
                                , record: r
                                , listeners: {
                                    'success': {
                                        fn: function () {
                                            this.refresh();
                                        }, scope: this
                                    }
                                }
                            });
                        }
                        this.windows.showItem.setTitle(_('notfoundparamalert_item_show') + ' ' + r.object.id);
                        this.windows.showItem.fp.getForm().reset();
                        this.windows.showItem.fp.getForm().setValues(r.object);
                        this.windows.showItem.show(e.target);
                    }, scope: this
                }
            }
        });
    }

    , removeItem: function (btn, e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('notfoundparamalert_item_remove')
            , text: _('notfoundparamalert_item_remove_confirm')
            , url: this.config.url
            , params: {
                action: 'mgr/item/remove'
                , id: this.menu.record.id
            }
            , listeners: {
                'success': {
                    fn: function (r) {
                        this.refresh();
                    }, scope: this
                }
            }
        });
    }

    , getSelectedAsList: function () {
        var sels = this.getSelectionModel().getSelections();
        if (sels.length <= 0) return false;

        var cs = '';
        for (var i = 0; i < sels.length; i++) {
            cs += ',' + sels[i].data.id;
        }
        cs = cs.substr(1);
        return cs;
    }

    , removeSelected: function (act, btn, e) {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('notfoundparamalert_items_remove')
            , text: _('notfoundparamalert_items_remove_confirm')
            , url: this.config.url
            , params: {
                action: 'mgr/items/remove'
                , items: cs
            }
            , listeners: {
                'success': {
                    fn: function (r) {
                        this.getSelectionModel().clearSelections(true);
                        this.refresh();
                        var t = Ext.getCmp('modx-resource-tree');
                        if (t) {
                            t.refresh();
                        }
                    }, scope: this
                }
            }
        });
        return true;
    }
});
Ext.reg('notfoundparamalert-grid-items', NotFoundParamAlert.grid.Items);


NotFoundParamAlert.window.ShowItem = function (config) {
    config = config || {};
    this.ident = config.ident || 'menuitem' + Ext.id();
    Ext.applyIf(config, {
        title: _('notfoundparamalert_item_show')
        , id: this.ident
        , height: 540
        , width: 1000
        , url: NotFoundParamAlert.config.connector_url
        , action: 'mgr/item/show'
        , fields: [
            {xtype: 'hidden', name: 'id', id: 'notfoundparamalert-' + this.ident + '-id'}
            ,{ xtype: 'textfield', fieldLabel: _('notfoundparamalert_url_full'), name: 'url_full', id: 'notfoundparamalert-' + this.ident + '-url_full', readOnly: true, anchor: '98%'}
            ,{ xtype: 'textfield', fieldLabel: _('notfoundparamalert_parameters_all'), name: 'parameters_all', id: 'notfoundparamalert-' + this.ident + '-parameters_all', readOnly: true, anchor: '98%'}
            ,{ xtype: 'textfield', fieldLabel: _('notfoundparamalert_parameters_found'), name: 'parameters_found', id: 'notfoundparamalert-' + this.ident + '-parameters_found', readOnly: true, anchor: '98%'}
            ,{ xtype: 'textfield', fieldLabel: _('notfoundparamalert_parameters_pattern'), name: 'parameters_pattern', id: 'notfoundparamalert-' + this.ident + '-parameters_pattern', readOnly: true, anchor: '98%'}
            ,{ xtype: 'textfield', fieldLabel: _('notfoundparamalert_ip_address'), name: 'ip_address', id: 'notfoundparamalert-' + this.ident + '-ip_address', readOnly: true, anchor: '98%'}
            ,{ xtype: 'textfield', fieldLabel: _('notfoundparamalert_timestamp'), name: 'time', id: 'notfoundparamalert-' + this.ident + '-time', readOnly: true, anchor: '60%'}
        ], buttons: [{
            text: _('close'),
            handler: function (w) {
                this.hide();
            },
            scope: this
        }]
    });
    NotFoundParamAlert.window.ShowItem.superclass.constructor.call(this, config);
};
Ext.extend(NotFoundParamAlert.window.ShowItem, MODx.Window);
Ext.reg('notfoundparamalert-window-item-show', NotFoundParamAlert.window.ShowItem);