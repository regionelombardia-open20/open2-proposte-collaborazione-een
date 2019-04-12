<?php

use lispa\amos\core\helpers\Html;
use lispa\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\EenExprOfInterestSearch $model
 */

$this->title = \lispa\amos\een\AmosEen::t('amoseen', '#expr_of_interest');
$this->params['breadcrumbs'][] = $this->title . ' '. \lispa\amos\een\AmosEen::t('amoseen', 'sended');
?>
<div class="een-expr-of-interest-index">
    <?php  echo $this->render('_search', ['model' => $model]); ?>

<!--    --><?php //$this->beginBlock('general'); ?>
    <?php echo \lispa\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,

            'columns' => [
                'eenPartnershipProposal.reference_external',
                'eenPartnershipProposal.content_title',
                [
                        'label' => \lispa\amos\een\AmosEen::t('amoseen', 'Status'),
                        'attribute' => 'workflowStatus.label',
                        'value' => function($model) {
                            $substatus = "";
                            if($model->sub_status) {
                                $substatus = '('.$model->getSubstatus()[$model->sub_status].')';
                            }
                            return \lispa\amos\een\AmosEen::t('amoseen', $model->workflowStatus->label). ' '. $substatus;
                        }
                ],

                [
                   'attribute' => 'is_request_more_info',
                    'value' => function($model){
                        if($model->is_request_more_info == 1){
                            return \lispa\amos\een\AmosEen::t('amoseen','Richiesta informazioni');
                        }
                        else return \lispa\amos\een\AmosEen::t('amoseen','#expr_of_interest');
                    },
                    'label' => \lispa\amos\een\AmosEen::t('amoseen', '#tipologia')

                ],
                [
                    'attribute' => 'een_staff_id',
                    'value' => function ($model) {
                        if (!empty($model->eenStaff->user->userProfile)) {
                            return $model->eenStaff->user->userProfile->nomeCognome;
                        } else return '';
                    },
                    'label' => \lispa\amos\een\AmosEen::t('amoseen', '#assigned_to'),
                ],
                [
                    'label' => \lispa\amos\een\AmosEen::t('amoseen', 'Presa in carico'),
                    'value' => function ($model) {
                        if($model->status != \lispa\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED){
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
                [
                    'class' => 'lispa\amos\core\views\grid\ActionColumn',
                    'template' => '{proposal_partnership}{view}{not_interested}',
                    'buttons' => [
                        'proposal_partnership' => function($url, $model){
                            /** @var $model \lispa\amos\een\models\EenExprOfInterest */
                            return Html::a(\lispa\amos\core\icons\AmosIcons::show('assignment'), ['/een/een-partnership-proposal/view', 'id' => $model->een_partnership_proposal_id], [
                                    'class' => ['btn btn-tools-secondary'],
                                    'title' => \lispa\amos\een\AmosEen::t('amoseen', '#partnership_proposal')
                            ]);
                        },
                        'view' => function($url, $model){
                            /** @var $model \lispa\amos\een\models\EenExprOfInterest */
                                return Html::a(\lispa\amos\core\icons\AmosIcons::show('file'), [$url], [
                                    'class' => ['btn btn-tools-secondary'],
                                    'title' => $model->is_request_more_info ? \lispa\amos\een\AmosEen::t('amoseen', '#view_request_info') : \lispa\amos\een\AmosEen::t('amoseen', '#view_edit_expr_of_interest')
                                ]);
                        },
                        'update' => function($url, $model){
                            /** @var $model \lispa\amos\een\models\EenExprOfInterest */
                            if($model->is_request_more_info == 0 &&  $model->status == \lispa\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED) {
                                return Html::a(\lispa\amos\core\icons\AmosIcons::show('edit'), [$url], [
                                    'class' => ['btn btn-tools-secondary'],
                                    'title' => \lispa\amos\een\AmosEen::t('amoseen', '#edit_expr_of_interest')
                                ]);
                            }
                            else return '';
                        },
                        'not_interested' => function($url, $model){
                            /** @var $model \lispa\amos\een\models\EenExprOfInterest */
                            if($model->status == \lispa\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED) {
                                return Html::a(\lispa\amos\core\icons\AmosIcons::show('close-circle'), ['/een/een-expr-of-interest/not-interested', 'id' => $model->id], [
                                    'class' => ['btn btn-danger-inverse'],
                                    'title' => \lispa\amos\een\AmosEen::t('amoseen', '#cancel_expr_of_interest'),
                                    'data-confirm' => \lispa\amos\een\AmosEen::t('amoseen', 'Sei sicuro di annullare la richiesta?')
                                ]);
                            }
                            else return '';
                        },
                    ]
                ],
        ],
    ]); ?>
<!--    --><?php //$this->endBlock(); ?>
    <div class="clearfix"></div>

<!--    --><?php
//    $itemsTab[] = [
//        'label' => \lispa\amos\een\AmosEen::tHtml('amoseen', '#expr_of_interest_sended'),
//        'content' => $this->blocks['general'],
//        'options' => ['id' => 'tab-general'],
//    ];
//    ?>
<!---->
<!---->
<!--    --><?php //if(\Yii::$app->user->can('STAFF_EEN')) { ?>
<!--        --><?php //$this->beginBlock('received'); ?>
<!---->
<!--        --><?php //echo $this->render('_tab_received_index', ['dataProviderReceived' => $dataProviderReceived]);
//        $this->endBlock(); ?>
<!--        <div class="clearfix"></div>-->
<!---->
<!--        --><?php
//        $itemsTab[] = [
//            'label' => \lispa\amos\een\AmosEen::tHtml('amoseen', 'Ricevute'),
//            'content' => $this->blocks['received'],
//            'options' => ['id' => 'tab-received'],
//        ];
//    } ?>
<!---->
<!--    --><?php //echo \lispa\amos\core\forms\Tabs::widget([
//        'encodeLabels' => false,
//        'items' => $itemsTab
//    ]); ?>
</div>



