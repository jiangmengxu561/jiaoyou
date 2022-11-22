define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'membercard/index' + location.search,
                    add_url: 'membercard/add',
                    edit_url: 'membercard/edit',
                    del_url: 'membercard/del',
                    multi_url: 'membercard/multi',
                    import_url: 'membercard/import',
                    table: 'membercard',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'member_name', title: __('Member_name'), operate: 'LIKE'},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'consumption', title: __('Consumption'), operate: 'LIKE'},
                        {field: 'extract', title: __('Extract')},
                        {field: 'choice', title: __('Choice')},
                        {field: 'choiceNumber', title: __('Choicenumber')},
                        {field: 'minimum', title: __('Minimum'), operate: 'LIKE'},
                        {field: 'Service', title: __('Service'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
