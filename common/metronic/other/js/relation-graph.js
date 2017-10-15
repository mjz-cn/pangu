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
    }
})();