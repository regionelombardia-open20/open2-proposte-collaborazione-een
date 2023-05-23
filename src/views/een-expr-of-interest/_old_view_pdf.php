<?php use open20\amos\een\AmosEen;?>

<?php if($model->is_request_more_info == 1) { ?>
    <h2><?= \open20\amos\een\AmosEen::t('amoseen', '#request_info')?></h2>
<?php  } else { ?>
    <h2><?= \open20\amos\een\AmosEen::t('amoseen', '#expr_of_interest')?></h2>
<?php  } ?>
<div class="container-general-info col-xs-12">
<?= yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'user.userProfile.nomeCognome',
        'eenPartnershipProposal.reference_external',
        'eenPartnershipProposal.content_title',
        [
            'attribute' => 'een_network_node_id',
            'value' => function($model){
                return $model->eenNetworkNode->name;
            }
        ],
        [
            'attribute' => 'een_staff_id',
            'value' => function($model){
                if(!empty($model->eenStaff->user->userProfile)) {
                    return $model->eenStaff->user->userProfile->nomeCognome;
                }
                else return '';
            },
            'label' => \open20\amos\een\AmosEen::t('amoseen','#staff_een_in_charge'),
        ],
        [
            'label' => \open20\amos\een\AmosEen::t('amoseen', '#status'),
            'value' => function($model){
                return \open20\amos\een\AmosEen::t('amoseen',$model->workflowStatus->label);
            }
        ],
        [
            'label' => \open20\amos\een\AmosEen::t('amoseen','#sub_status'),
            'value' => function($model){
                if(!empty(\open20\amos\een\models\EenExprOfInterest::getSubstatus()[$model->sub_status])) {
                    return \open20\amos\een\models\EenExprOfInterest::getSubstatus()[$model->sub_status];
                }
            }
        ],
        [
            'attribute'=>'created_at',
            'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
        ],
        [
            'attribute'=>'updated_at',
            'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
        ],
    ],
    'options' => ['class' => 'table-info']

]) ?>
    <p>
        <strong><?=\open20\amos\een\AmosEen::t('amoseen', 'Note') . ':'?></strong><br>
        <?= $model->note?>
    </p>
</div>
<br>
<?php if($model->is_request_more_info == 0 && $model->een_network_node_id == 1) { ?>
    <h4><?= \open20\amos\een\AmosEen::t('amoseen', '#organization_data')?></h4>
    <div class="container-general-info col-xs-12">
        <?= \yii\widgets\DetailView::widget([
            'model' => $model,
            'attributes' => [
                'company_organization',
                'sector',
                'address',
                'city',
                'postal_code',
                'web_site',
                'contact_person',
                'phone',
                'email:email',
            ],
            'options' => ['class' => 'table-info']

        ]); ?>
        <p>
            <strong><?=\open20\amos\een\AmosEen::t('amoseen', '#question_technology_interest') . ':'?></strong><br>
            <?= $model->technology_interest?>
        </p>
        <p>
            <strong><?=\open20\amos\een\AmosEen::t('amoseen', '#question_organization_presentation') . ':'?></strong><br>
            <?= $model->organization_presentation?>
        </p>
        <p>
            <strong><?=\open20\amos\een\AmosEen::t('amoseen', '#question_information_request1') . ':'?></strong><br>
            <?= $model->information_request?>
        </p>
    </div>

<?php } ?>
<br>
<h4><?= \open20\amos\een\AmosEen::t('amoseen', '#user_data')?></h4>
<div class="container-general-info col-xs-12">
    <?= \yii\widgets\DetailView::widget([
        'model' => $profile,
        'attributes' => [
            'nomeCognome',
            'telefono',
            'user.email',
            [
                'attribute' => 'user_profile_role_id',
                'value' => function($model) {
                    if ($model->userProfileRole) {

                        if ($model->userProfileRole->id == 7) {
                            return $model->user_profile_role_other;
                        }
                        return $model->userProfileRole->name;
                    }
                },
                'label' => AmosEen::t('amoseen', '#role')

            ],
            [
                'attribute' => 'user_profile_area_id',
                'value' => function($model) {
                    if ($model->userProfileArea) {
                        return $model->userProfileArea->name;
                    }
                },
                'label' => AmosEen::t('amoseen', 'Area')

            ],
            [
                'attribute' => 'prevalent_partenership_id',
                'value' => function($model){
                    if($model->prevalentPartnership){
                        return $model->prevalentPartnership->name;
                    }
                },
                'label' => AmosEen::t('amoseen', '#prevalent_partnership')
            ]
        ],
        'options' => ['class' => 'table-info']

    ]); ?>
    <p>
        <strong><?=\open20\amos\een\AmosEen::t('amoseen', '#presentazione_personale') . ':'?></strong><br>
        <?= $profile->presentazione_breve?>
    </p>
    <p>
        <strong><?=\open20\amos\een\AmosEen::t('amoseen', '#presentazione_professionale') . ':'?></strong><br>
        <?= $profile->presentazione_personale?>
    </p>
    <h5><strong><?=AmosEen::t('amoseen', 'Aree di interesse') . ': '?></strong></h5>
    <?php if (!empty(\Yii::$app->getModule('tag'))) { ?>
        <div class="tags-section-sidebar col-xs-12 nop" id="section-tags">
            <?= \open20\amos\core\forms\ListTagsWidget::widget([
                'userProfile' => $profile->id,
                'className' => $profile->className(),
                'viewFilesCounter' => false,
                'withTitle' => false,
                'pageSize' => 10000
            ]);
            ?>
        </div>
    <?php } ?>
    <br>
    <br>
    <div style="font-style: italic">
        <div class="col-lg-12">
            <p>
                <?= AmosEen::t('amoseen', '#information_text1') ?>
            </p>
            <p class="text-center">
                <?= AmosEen::t('amoseen', '#information_text2') ?>
            </p>
            <p>
                <?= AmosEen::t('amoseen', '#information_text3') ?>
            </p>
        </div>
        <div class="col-lg-12 m-t-20">
            <p>
                <?= AmosEen::t('amoseen', '#information_text4') ?>
            </p>
        </div>
        <div class="col-lg-12 col-sm-12">
        L'utente ha preso visione dell'informativa sul trattamento dati
        </div>
        <div class="col-lg-12 col-sm-12">
            <?=AmosEen::t('amoseen', 'Il PDF è stato generato automaticamente dalla piattaforma previa autenticazione del richiedente ')?>
        </div>
    </div>
</div>
