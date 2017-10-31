<?php
/**
 * Created by PhpStorm.
 * User: mjz
 * Date: 17/10/20
 * Time: 下午3:01
 */

use common\helpers\TransactionHelper;
use common\models\records\ExchangeLog;
use kartik\helpers\Html;
use common\core\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \yii\base\Model  */
/* @var $hiddenFields   array|null  要隐藏在表单中的field */
/* @var $shownFields   array|null   要展示在在表单中的field */
/* @var $hideSearch   boolean       是否展示索框框 */

$columns = [];

?>

<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase">管理信息</span>
        </div>
    </div>
    <div class="portlet-body">
        <?php \yii\widgets\Pjax::begin(['options' => ['id' => 'pjax-container']]); ?>
        <?php if ($hideSearch !== true) {
            echo '<div>';
            echo $this->render('_common_user_search', [
                'model' => $searchModel,
                'hiddenFields' => $hiddenFields,
            ]);
            echo '</div>';
        } ?>
        <!-- 条件搜索-->
        <div class="table-container">
            <form class="ids">
                <input name="<?= Yii::$app->request->csrfParam ?>" type="hidden"
                       id="<?= Yii::$app->request->csrfParam ?>"
                       value="<?= Yii::$app->request->csrfToken ?>">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider, // 列表数据
                    'columns' => $columns
                ]); ?>
            </form>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
