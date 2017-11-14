(function () {

    var form = $('#user-tree-search-form');
    form.submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function (resp) {
                if (resp.status !== 1) {
                    updateAlert(resp.info);
                    setTimeout(function () {
                        if (resp.url) {
                            //location.href=data.url;
                        } else {
                            $('#top-alert').find('button').click();
                        }
                    }, 3000);
                } else {
                    // 更新整个二叉树
                    if (resp.data !== null) {
                        updateTreeGraph(resp.data);
                    }
                }
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            }
        });
    });

    function updateTreeGraph(data) {
        var g_chart_config = {
            chart: {
                container: "#user-tree-container",
                nodeAlign: "BOTTOM",
                connectors: {
                    type: 'step'
                },
                animateOnInit: true,
                rootOrientation: 'WEST',
                scrollbar: "fancy",
                animation: {
                    nodeAnimation: "easeOutBounce",
                    nodeSpeed: 700,
                    connectorsAnimation: "linear",
                    connectorsSpeed: 700
                },
                node: {
                    HTMLclass: 'node-user',
                    collapsable: true
                }
            },
            nodeStructure: data
        };

        // 生成二叉树
        new Treant(g_chart_config);
        // 重新定义node里面的内容
        $('.node111').each(function () {
            var that = $(this);
            // that.empty();
            var table = $('.node-table-tpl').clone();
            table.removeClass('node-table-tpl');
            // 获取所有数据
            that.find('[class^=node-]').each(function () {
                var nodeThat = $(this);
                var css = nodeThat.attr('class');
                table.attr('data-' + css.replace('node-', ''), nodeThat.text());
                // 获取level-data 数据
                table.find('.' + css).text(nodeThat.text());
            });
            that.html(table);
        });
    }

    // 如果url中包含user_id则执行搜索
    // var user_id = urlParam('user_id');
    // if (user_id !== null) {
    //     form.trigger('submit');
    // }
    form.trigger('submit');
})();