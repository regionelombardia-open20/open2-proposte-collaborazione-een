<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

$this->title = \open20\amos\een\AmosEen::t('amoseen', '#expr_of_interest');
$breadcrumb= $this->title . ' '. \open20\amos\een\AmosEen::t('amoseen', 'received');
$this->params['breadcrumbs'][] = $breadcrumb;


$js = <<<JS
 //--- SHOW the modal
    $(document).on('click','.transfer', function() {
        $('#modal_transfer').modal('show')
            .find('#modal_content')
            .load($(this).attr('value'));
        console.log($(this).attr('value'));
    });

JS;
$this->registerJs($js);?>

<div class="een-expr-of-interest-index">
<?php echo $this->render('_search', ['model' => $model]); ?>
<?php
   echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
            'columns' => [
                'eenPartnershipProposal.reference_external',
                'eenPartnershipProposal.content_title',
                [
                    'label' => \open20\amos\een\AmosEen::t('amoseen', '#eoi_sended_by'),
                    'attribute' => 'user.userProfile.nomeCognome',
                ],
                [
                    'label' => \open20\amos\een\AmosEen::t('amoseen', 'Status'),
                    'attribute' => 'workflowStatus.label',
                    'value' => function($model) {
                        return \open20\amos\een\AmosEen::t('amoseen', $model->workflowStatus->label);
                    }
                ],
                [
                    'attribute' => 'is_request_more_info',
                    'value' => function($model){
                        if($model->is_request_more_info == 1){
                            return \open20\amos\een\AmosEen::t('amoseen','Richiesta informazioni');
                        }
                        else return \open20\amos\een\AmosEen::t('amoseen','#expr_of_interest');
                    },
                    'label' => \open20\amos\een\AmosEen::t('amoseen', '#tipologia')

                ],
                [
                    'attribute' => 'een_staff_id',
                    'value' => function ($model) {
                        if (!empty($model->eenStaff->user->userProfile)) {
                            return $model->eenStaff->user->userProfile->nomeCognome;
                        } else return '';
                    },
                    'label' => \open20\amos\een\AmosEen::t('amoseen', '#assigned_to'),
                ],
                [
                    'label' => \open20\amos\een\AmosEen::t('amoseen', 'Presa in carico'),
                    'value' => function ($model) {
                        if($model->status != \open20\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED && !empty($model->een_staff_id)){
                            return true;
                        }
                        else return false;
                    },
                    'format' => 'boolean'
                ],
                [
                    'attribute'=>'created_at',
                    'format'=> 'datetime',
                ],
//            ['attribute'=>'created_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'deleted_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'created_by','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'updated_by','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'deleted_by','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{proposal_partnership}{view}{transfer}{history}',
                    'buttons' => [
                        'proposal_partnership' => function($url, $model){
                            /** @var $model \open20\amos\een\models\EenExprOfInterest */
                            return Html::a(\open20\amos\core\icons\AmosIcons::show('assignment'), ['/een/een-partnership-proposal/view', 'id' => $model->een_partnership_proposal_id], [
                                'class' => ['btn btn-tools-secondary'],
                                'title' => \open20\amos\een\AmosEen::t('amoseen', '#partnership_proposal')
                            ]);
                        },
                        'history' => function ($url, $model) {
                                return $this->render('_history_expr_of_int_modal', [
                                    'model' => $model,
                                ]);
                        },
                        'transfer' => function ($url, $model) {
                            if($model->status != \open20\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED && !empty($model->een_staff_id)) {
                                return Html::a(\open20\amos\core\icons\AmosIcons::show('refresh'), 'javascript:void(0)',
                                    [
                                        'model' => $model,
                                        'value' => \Yii::$app->urlManager->createUrl(['/een/een-expr-of-interest/transfer-expr-of-interest', 'id' => $model->id, 'ajax' => true]),
                                        'class' => 'btn btn-tools-secondary transfer',
                                        'title' => Yii::t('amoseen', '#transfer_expr_of_interest'),
                                    ]
                                );
                            }
                            return '';
                        },
                        'view' => function($url, $model){
                            /** @var $model \open20\amos\een\models\EenExprOfInterest */
                                return Html::a(\open20\amos\core\icons\AmosIcons::show('file'), [$url], [
                                    'class' => ['btn btn-tools-secondary'],
                                    'title' => $model->is_request_more_info ? \open20\amos\een\AmosEen::t('amoseen', '#view_request_info') : \open20\amos\een\AmosEen::t('amoseen', '#view_edit_expr_of_interest')
                                ]);
                        },

                    ]
                ]
        ],
    ]); ?>
</div>

<?php
\yii\bootstrap\Modal::begin([
    'id' => 'modal_transfer',
    'header' => '<h2>'.\open20\amos\een\AmosEen::t('amoseen','#transfer_expr_of_interest').'</h2>',
    'size' => \yii\bootstrap\Modal::SIZE_LARGE,
//    'toggleButton' => ['label' => \open20\amos\core\icons\AmosIcons::show('time'), 'class' => 'btn btn-tool-secondary'],
]);
?>
<div id='modal_content'></div>

<?php
\yii\bootstrap\Modal::end();
?>

