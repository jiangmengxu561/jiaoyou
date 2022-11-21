define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/user/index',
                    add_url: 'user/user/add',
                    edit_url: 'user/user/edit',
                    del_url: 'user/user/del',
                    multi_url: 'user/user/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'user.id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true},
                        // {field: 'group.name', title: __('Group')},
                        {field: 'username', title: __('Username'), operate: 'LIKE'},
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        // {field: 'email', title: __('Email'), operate: 'LIKE'},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'avatar', title: __('Avatar'), events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        {field: 'level', title: __('Level'), operate: 'BETWEEN', sortable: true},
                        {field: 'gender', title: __('Gender'), visible: false, searchList: {1: __('Male'), 0: __('Female')}},
                        {field: 'money', title: __('余额'), operate: 'BETWEEN', sortable: true},
                        {field: 'ismember', title: __('是否会员'), visible: false, searchList: {1: __('是'), 0: __('否')}},
                        {field: 'isrealname', title: __('是否实名'),visible: false, searchList: {1: __('是'), 0: __('否')}},
                        {field: 'bio', title: __('签名'), operate: 'BETWEEN', sortable: true},
                        {field: 'WeChatNumber', title: __('微信号'), operate: 'BETWEEN', sortable: true},
                        {field: 'phoneNumber', title: __('手机号'), operate: 'BETWEEN', sortable: true},
                        {field: 'QQNumber', title: __('qq号'), operate: 'BETWEEN', sortable: true},
                        // {field: 'answer', title: __('答题卡'), operate: 'BETWEEN', sortable: true},
                        // {field: 'skip', title: __('跳过卡'), operate: 'BETWEEN', sortable: true},
                        // {field: 'delete', title: __('评论删除卡'), operate: 'BETWEEN', sortable: true},
                        {field: 'album_images', title: __('相册'),  events: Table.api.events.images, formatter: Table.api.formatter.images, operate: false},
                        {field: 'video_files', title: __('视频'), operate: false, events: Table.api.events.file, formatter: Table.api.formatter.files},
                        {field: 'expiretime', title: __('会员到期时间'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange',sortable: true},
                        // {field: 'successions', title: __('Successions'), visible: false, operate: 'BETWEEN', sortable: true},
                        // {field: 'maxsuccessions', title: __('Maxsuccessions'), visible: false, operate: 'BETWEEN', sortable: true},
                        // {field: 'logintime', title: __('Logintime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        // {field: 'loginip', title: __('Loginip'), formatter: Table.api.formatter.search},
                        // {field: 'jointime', title: __('Jointime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        // {field: 'joinip', title: __('Joinip'), formatter: Table.api.formatter.search},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, searchList: {normal: __('Normal'), hidden: __('Hidden')}},
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