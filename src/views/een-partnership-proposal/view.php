<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\views\een-partnership-proposal
 * @category   CategoryName
 */

use lispa\amos\core\forms\Tabs;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\een\AmosEen;
use lispa\amos\een\models\EenPartnershipProposal;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use lispa\amos\core\forms\ActiveForm;


/**
 * @var yii\web\View $this
 * @var EenPartnershipProposal $model
 */

$this->title = strip_tags($model->content_title);
$this->params['breadcrumbs'][] = [
    'label' => AmosEen::t('amoseen', 'Proposte Di Collaborazione EEN'),
    'url' => ['/een-partnership-proposal']
];
$this->params['breadcrumbs'][] = [
    'label' => AmosEen::t('amoseen', 'Proposte Di Collaborazione Een'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

// Tab ids
$idTabCard = 'tab-card';
$idTabPartnership = 'tab-partnership';
$idTabOrganization = 'tab-organization';
$idTabPartnereen = 'tab-partnereen';
$idClassifications = 'tab-classifications';
$idTabAttachments = 'tab-attachments';
$isArchived = $model->isArchived();

?>

<div class="proposte-di-collaborazione-view post-details col-xs-12">
    <?php $this->beginBlock('card'); ?>
    <div class="post col-xs-12 nop nom">
        <div class="post-content col-xs-12 nop">
            <div class="post-title col-xs-10">
                <h2><?= $model->content_title ?></h2>
            </div>
            <div class="col-xs-12 m-b-10 nop">
                <i><?= AmosEen::t('amoseen','Identificativo proposta: ').$model->reference_external ?></i>
                    <?php if($model->isExprOfInterestSended()) {
                        echo Html::a(AmosEen::t('amoseen', '#request_sended'), '/een/een-expr-of-interest', ['class' => 'pull-right']);
                    }?>
                    <?php if(!$model->isExprOfInterestSended() && !$isArchived) { ?>
                        <a class= 'btn btn-navigation-primary pull-right' href="#<?='interestPopup-'.$model->id?>" id="<?=$model->id?>" title="<?= AmosEen::t('amoseen', '#expr_of_interest_request_more_info') ?>" data-target="#interestPopup-<?= $model->id?>" data-toggle="modal"><?= AmosEen::t('amoseen', '#expr_of_interest_request_more_info') ?></a>
                    <?php } ?>
            </div>
            <div class="col-xs-12 nop list-container-proposta-collaborazione-een">
                <?php
                if(false){
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->attachments)) {
                        $url = $model->attachments[0]->getUrl('original');
                    }
                    ?>
                    <div class="post-image-right">
                        <?= Html::img($url, ['class' => '']) ?>
                    </div>
                    <?php
                }
                ?>
                <div class="post-text">
                    <?php if($model->content_summary){ ?>
                    <p><?= $model->content_summary ?></p>
                    <?php } ?>
                    <br />
                    <?php if($model->content_description){ ?>
                    <p><?= $model->content_description ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="post-info col-xs-6">
            <span class="bold"><?= $model->getAttributeLabel('reference_external') ?>:</span>
            <span><?= $model->reference_external ?></span>
            <br>
            <span class="bold"><?= $model->getAttributeLabel('reference_type') ?>:</span>
            <span><?= $model->getReferenceTypeLabel() ?></span>
            <br>
            <span class="bold"><?= $model->getAttributeLabel('company_country_label') ?>:</span>
            <span><?= $model->company_country_label ?></span>
            <br>
        </div>
        <div class="post-info col-xs-6">
            <span class="bold"><?= $model->getAttributeLabel('datum_submit') ?>:</span>
            <span><?= Yii::$app->getFormatter()->asDate($model->datum_submit) ?></span>
            <br>
            <span class="bold"><?= $model->getAttributeLabel('datum_update') ?>:</span>
            <span><?= Yii::$app->getFormatter()->asDate($model->datum_update) ?></span>
            <br>
            <span class="bold"><?= $model->getAttributeLabel('datum_deadline') ?>:</span>
            <span><?= Yii::$app->getFormatter()->asDate($model->datum_deadline) ?></span>
        </div>
        <?php if(false){ ?>
        <div class="container-general-info col-xs-12 nop">
            <h3 class="title"><?= AmosIcons::show('info-outline'); ?> <?= AmosEen::tHtml('amoseen', 'Informazioni') ?></h3>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'cooperation_stagedev_stage',
                    'cooperation_stagedev_comment',
                    'cooperation_ipr_status',
                    'cooperation_ipr_comment',
                    'cooperation_partner_area',
                    'cooperation_partner_sought',
                    'company_kind',
                    'company_turnover',
                    'company_since',
                    'company_transnational:boolean',
                    'contact_consortium',
                    [
                        'label' => $model->getAttributeLabel('contact_consortiumcountry_label'),
                        'value' => function ($model) {
                            return "$model->contact_consortiumcountry_label ({$model->contact_consortiumcountry_key})";
                        },
                    ],
                    'contact_organization',
                    'contact_email:email',
                    'contact_phone',
                    'contact_partnerid',
                ],
                'options' => ['class' => 'table-info']
            ]) ?>
        </div>
        <?php } ?>
    </div>
    <?php $this->endBlock(); ?>
    
    <?php
    $itemsTab[] = [
        'label' => AmosEen::t('amoseen', 'Scheda'),
        'content' => $this->blocks['card'],
        'options' => ['id' => $idTabCard],
    ];
    ?>

    <?php $this->beginBlock('partnership'); ?>
    <div class="post col-xs-12 nop nom">
            <?php if($model->isExprOfInterestSended()) {
                echo Html::a(AmosEen::t('amoseen', '#request_sended'), '/een/een-expr-of-interest', ['class' => 'pull-right']);
            }?>
            <?php if(!$model->isExprOfInterestSended() && !$isArchived) { ?>
                <a class= 'btn btn-navigation-primary pull-right' href="#<?='interestPopup-'.$model->id?>" id="<?=$model->id?>" title="<?= AmosEen::t('amoseen', '#expr_of_interest_request_more_info') ?>" data-target="#interestPopup-<?= $model->id?>" data-toggle="modal"><?= AmosEen::t('amoseen', '#expr_of_interest_request_more_info') ?></a>
            <?php } ?>
        <div class="post-content col-xs-12 nop">
            <h3><?= AmosEen::t('amoseen', 'Partnership')?></h3>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'cooperation_stagedev_stage',
                    'cooperation_stagedev_comment',
                    'cooperation_ipr_status',
                    'cooperation_ipr_comment',
                    'cooperation_partner_area',
                    'cooperation_partner_sought',
                ],
                'options' => ['class' => 'table-info']
            ]) ?>
        </div>
    </div>
<br>
    <div class="post col-xs-12 nop m-t-30 m-l-0">
        <div class="post-content col-xs-12 nop">
            <h3><?= AmosEen::t('amoseen', '#proponent')?></h3>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'company_kind',
                    'company_turnover',
                    'company_since',
                    'company_transnational:boolean',
                ],
                'options' => ['class' => 'table-info']
            ]) ?>
        </div>
    </div>

    <?php if (Yii::$app->getModule('tag')): ?>
        <div class="col-xs-12 m-t-30 m-l-0 nop">
            <?= \lispa\amos\core\forms\ShowUserTagsWidget::widget([
                'userProfile' => $model->id,
                'className' => $model->className()
            ]);
            ?>
        </div>
    <?php endif; ?>

    <div class="allegati col-xs-12 nop m-t-30">
        <!-- TODO sostituire il tag h3 con il tag p e applicare una classe per ridimensionare correttamente il testo per accessibilità -->
        <h3><?= AmosEen::tHtml('amoseen', 'Allegati') ?></h3>
        <?= \lispa\amos\attachments\components\AttachmentsTableWithPreview::widget([
            'model' => $model,
            'attribute' => 'attachments',
            'viewDeleteBtn' => false
        ]) ?>
    </div>
    <?php $this->endBlock(); ?>

    <?php
    $itemsTab[] = [
        'label' => AmosEen::t('amoseen', '#insights'),
        'content' => $this->blocks['partnership'],
        'options' => ['id' => $idTabPartnership],
    ];
    ?>


    <?php if(\Yii::$app->user->can('ADMIN') || \Yii::$app->user->can('STAFF_EEN')) {?>
        <?php $this->beginBlock('partner_een'); ?>
        <div class="post col-xs-12 nop nom">
            <div class="post-content col-xs-12 nop">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'contact_consortium',
                        [
                            'label' => $model->getAttributeLabel('contact_consortiumcountry_label'),
                            'value' => function ($model) {
                                return "$model->contact_consortiumcountry_label ({$model->contact_consortiumcountry_key})";
                            },
                        ],
                        'contact_organization',
                        'contact_email:email',
                        'contact_phone',
                        'contact_partnerid',
                    ],
                    'options' => ['class' => 'table-info']
                ]) ?>
            </div>
        </div>
        <?php $this->endBlock(); ?>
        <?php
        $itemsTab[] = [
            'label' => AmosEen::t('amoseen', 'Partner EEN'),
            'content' => $this->blocks['partner_een'],
            'options' => ['id' => $idTabPartnereen],
        ];
        ?>
    <?php  } ?>







    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>
    <p>

<?php
    echo $this->render('_modal_expr_of_interest', ['model' => $model]);




//        $form = ActiveForm::begin([
//            'action' => \Yii::$app->urlManager->createUrl([\Yii::$app->controller->module->id.'/een-partnership-proposal/send-interest']),
//            'method' => 'post',
//            'id' => 'form'.$model->id
//        ]);
//
//        echo Html::hiddenInput('InfoReqModel[een]',$model->id);
//        echo "<p>". AmosEen::t('amoseen', '#region') . "</p>";
//        echo Html::dropDownList('InfoReqModel[region]',"", [
//            "" => "",
//            'Lombardia / Emilia Romagna' => 'Lombardia / Emilia Romagna',
//            'Piemonte / Val d&apos;Aosta / Liguria' => 'Piemonte / Val d&apos;Aosta / Liguria',
//            'Veneto / Friuli Venezia Giulia / Trentino Alto Adige' => 'Veneto / Friuli Venezia Giulia / Trentino Alto Adige',
//            'Toscana / Umbria / Marche' => 'Toscana / Umbria / Marche',
//            'Lazio / Sardegna' => 'Lazio / Sardegna',
//            'Abruzzo / Molise / Campagna / Puglia / Basilicata / Calabria / Sicilia' => 'Abruzzo / Molise / Campagna / Puglia / Basilicata / Calabria / Sicilia'
//        ]);
//        echo "<p>";
//        echo "<p>". AmosEen::t('amoseen', '#enterprisenetwork') . "</p>";
//        echo Html::radio('InfoReqModel[ent]', false, ['label' => AmosEen::t('amoseen', '#yes'), 'value' => 1]);
//        echo Html::radio('InfoReqModel[ent]', true, ['label' => AmosEen::t('amoseen', '#no'), 'value' => 0]);
//        echo "</p>";
//        echo "<p>". AmosEen::t('amoseen', '#confirm_message') . "</p>";
//        echo "<div class='modal-footer'>";
//        echo Html::submitButton( AmosEen::t('amoseen', '#inforequest'),['class' => 'btn btn-navigation-primary pull-right']);
//        echo "</div>";
//        ActiveForm::end();

        ?>
</div>
