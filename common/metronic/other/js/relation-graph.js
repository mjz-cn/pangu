(function () {

    // 如何将异步生成的数据加载到页面中
    $('.ajax-get-relation').click(function () {
        var that = this;
        var target_form = $(this).attr('target-form');
        var form = $('.' + target_form);
        // 目前全部更新
        $.get(form.get(0).action, form.serialize()).success(
            function (resp) {
                if (resp.status !== 1) {
                    updateAlert(resp.info);
                    setTimeout(function () {
                        if (resp.url) {
                            //location.href=data.url;
                        } else {
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled', false);
                        }
                    }, 3000);
                } else {
                    // 更新整个二叉树
                    updateTreeGraph(resp.data);
                }
            }
        );
        // 发送请求
        return false;
    });

    function updateTreeGraph(data) {
        var g_chart_config = {
            chart: {
                container: "#relation-graph",
                nodeAlign: "BOTTOM",
                connectors: {
                    type: 'step'
                },
                node: {
                    HTMLclass: 'node-user'
                }
            },
            nodeStructure: data
        };
        // 生成二叉树
        new Treant(g_chart_config);
        // 重新定义node里面的内容
        $('.node').each(function() {
            var that = $(this);
            // that.empty();
            var table = $('.node-table-tpl').clone();
            table.removeClass('node-table-tpl');
            // 获取所有数据
            that.find('[class^=node-]').each(function() {
                var nodeThat = $(this);
                var css = nodeThat.attr('class');
                table.attr('data-' + css.replace('node-', ''), nodeThat.text());
                table.find('.' + css).text(nodeThat.text());
            });
            that.html(table);
        });
    }

    // 如果url中包含user_id则执行任务
    var user_id = urlParam('user_id');
    if (user_id !== null) {
        $('.ajax-get-relation').click();
    }
})();