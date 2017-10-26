<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/24
 * Time: 下午10:40
 */

namespace common\core;

class GridView extends \yii\grid\GridView
{

    public $dataColumnClass = 'common\core\DefaultDataColumn';
    public $options = ['class' => 'grid-view'];
    public $tableOptions = ['class' => 'table table-striped table-bordered table-condensed table-hover order-column dataTable no-footer'];
    public $layout = '{items}<div class=""><div class="col-md-5 col-sm-5">{summary}</div><div class="col-md-7 col-sm-7">
                    <div class="dataTables_paginate paging_bootstrap_full_number" style="text-align:right;">{pager}</div></div></div>';
    public $summaryOptions = ['class' => 'pagination'];
    public $pager = [
        'options' => ['class' => 'pagination', 'style' => 'visibility: visible;'],
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '上一页',
        'firstPageLabel' => '第一页',
        'lastPageLabel' => '最后页'
    ];
}