<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var backend\models\EenExprOfInterest $model
*/

$this->title = Yii::t('cruds', 'Aggiorna {modelClass}', [
    'modelClass' => 'Een Expr Of Interest',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Een Expr Of Interest'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Aggiorna');
?>
<div class="een-expr-of-interest-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelEenPartenership' => $modelEenPartenership
    ]) ?>

</div>
