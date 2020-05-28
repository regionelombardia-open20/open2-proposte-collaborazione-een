<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */ use open20\amos\een\AmosEen;
/**
 * @var $model \open20\amos\een\models\EenExprOfInterest
 * */
?>


<p style="text-align: center">
    <?= AmosEen::t('amoseen', "<strong>Piattaforma regionale di Open Innovation</strong><br>
    <a href=\"www.openinnovation.regione.lombardia.it\">www.openinnovation.regione.lombardia.it</a>
")?>
</p>

<p style="text-align: center">
    <?= AmosEen::t('amoseen', "<strong>Manifestazione di interesse in una proposta di collaborazione generata nell’ambito di Enterprise Europe Network</strong>
")?>
</p>

<p style="text-align: center">
    <?= AmosEen::t('amoseen', "<strong>Iniziativa realizzata in collaborazione con Finlombarda s.p.a. in qualità di coordinatore del Progetto SIMPLER (FPA n. 649589) </strong>
")?>
</p>

<p style="text-align: center">
  <strong>  <?= AmosEen::t('amoseen', "SEZIONE 1")?></strong><br>
<?= AmosEen::t('amoseen', "(Questa sezione, redatta in lingua inglese, contiene le informazioni che saranno trasferite al centro EEN che assiste il soggetto che ha pubblicato la proposta di collaborazione, v.informativa in calce per dettagli)
")?>
</p>
<br>
<?php if($model->is_request_more_info == 1) { ?>
    <h4 style="text-align: center"><strong><?= AmosEen::t('amoseen', "REQUESTO MORE INFO")?></strong></h4>
<?php  } else { ?>
    <h4 style="text-align: center"><strong><?= AmosEen::t('amoseen', "EXPRESSION OF INTEREST")?></strong></h4>
<?php  } ?>
<div class="container-general-info col-xs-12">
<table  style="width: 100%;border: 1px solid; border-collapse: collapse; ">
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Proposal ID")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->eenPartnershipProposal->reference_external?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Proposal title")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->eenPartnershipProposal->content_title?></td>
    </tr>
</table>
<br>
<table style="width: 100%;border: 1px solid; border-collapse: collapse; ">
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Contact person1")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->contact_person?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Organization1")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->company_organization?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Sector/Activities1")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->sector?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Address1")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->address?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "City1")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->city?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Postal code1")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->postal_code?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Web site1")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->web_site?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Phone1")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->phone?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Email")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->email?></td>
    </tr>
    <tr>
        <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Your EEN region")?></td>
        <td style="width: auto;border: 1px solid;"><?= $model->eenNetworkNode->name ?></td>
    </tr>
    <?php if(!empty($model->eenStaff->user->userProfile)) { ?>
        <tr>
            <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Designated EEN contact")?></td>
            <td style="width: auto;border: 1px solid;"><?= $model->eenStaff->user->userProfile->nomeCognome?></td>
        </tr>
    <?php } ?>
</table>
<br>
    <table style="width: 100%">
        <tr>
            <td><?= AmosEen::t('amoseen', "What kind of cooperation are you looking for?")?></td>
        </tr>
        <tr>
            <td  style="width: 100%; min-height: 50px; border: 1px solid black;">
                <?= $model->technology_interest ?>
            </td>
        </tr>
    </table>
<br>
    <table style="width: 100%">
        <tr>
            <td><?= AmosEen::t('amoseen', "Which information is missing or unclear?")?></td>
        </tr>
        <tr>
            <td style="width: 100%; min-height: 50px; border: 1px solid black;">
                <?= $model->information_request ?>
            </td>
        </tr>
    </table>

    <br>
    <table style="width: 100%">
        <tr>
            <td><?= AmosEen::t('amoseen', "Some facts about your company") ?> </td>
        </tr>
        <tr>
            <td style="width: 100%; min-height: 50px; border: 1px solid black;">
                <?= $model->organization_presentation ?>
            </td>
        </tr>
    </table>

</div>

<pagebreak />

    <p style="text-align: center">
        <strong><?= AmosEen::t('amoseen', "SEZIONE 2")?></strong><br>
        <?= AmosEen::t('amoseen', "(Questa sezione raccoglie le informazioni che saranno trasferite al centro EEN competente per territorio che assisterà il soggetto che ha fatto la manifestazione di interesse)")?>
    </p>

<br>
<div class="container-general-info col-xs-12">
    <table style="width: 100%;border: 1px solid; border-collapse: collapse; ">
                <tr>
                    <td style="width: 20%;border: 1px solid;"><?= AmosEen::t('amoseen', "Denominazione")?></td>
                    <td style="width: 80%;border: 1px solid;"><?php echo !empty($model->organization) ? $model->organization->name : ''?></td>
                </tr>
                <tr>
                    <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "indirizzo")?></td>
                    <td style="width: auto;border: 1px solid;"><?php echo !empty($model->organization) ? $model->organization->getAddressField() : ''?></td>
                </tr>
                <tr>
                    <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Referente operativo<")?>/td>
                    <td style="width: auto;border: 1px solid;"><?php echo !empty($model->organization->operatingReferent) ? $model->organization->operatingReferent->userProfile->nomeCognome : ''?></td>
                </tr>
                <tr>
                    <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Codice ATECO")?></td>
                    <td style="width: auto;border: 1px solid;"><?php echo !empty($model->organization) ? $model->organization->ateco_code : ''?></td>
                </tr>
                <tr>
                    <td style="width: auto;border: 1px solid;"><?= AmosEen::t('amoseen', "Indirizzo web")?></td>
                    <td style="width: auto;border: 1px solid;"><?php echo !empty($model->organization) ? $model->organization->web_site : ''?></td>
                </tr>
    </table>
<!--    <table style="width: 100%;border: 1px solid; border-collapse: collapse; ">-->
<!--        <tr>-->
<!--            <td style="width: auto;border: 1px solid;">Name and surname</td>-->
<!--            <td style="width: auto;border: 1px solid;">--><?php //echo $profile->nomeCognome?><!--</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width: auto;border: 1px solid;">Phone</td>-->
<!--            <td style="width: auto;border: 1px solid;">--><?php //echo $profile->telefono?><!--</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width: auto;border: 1px solid;">Email</td>-->
<!--            <td style="width: auto;border: 1px solid;">--><?php //echo $profile->user->email?><!--</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width: auto;border: 1px solid;">Role</td>-->
<!--            <td style="width: auto;border: 1px solid;">--><?php
//                if ($profile->userProfileRole) {
//                    if ($profile->userProfileRole->id == 7) {
//                        return $profile->user_profile_role_other;
//                    }
//                    echo $profile->userProfileRole->name;
//                }?>
<!--            </td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width: auto;border: 1px solid;">Prevalent partenership</td>-->
<!--            <td style="width: auto;border: 1px solid;">--><?php
//                if($profile->prevalentPartnership){
//                    echo $profile->prevalentPartnership;
//                }?>
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->
<!--<br>-->
<!--    <table style="width: 100%">-->
<!--        <tr>-->
<!--            <td>Personal presentation</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width: 100%; min-height: 100px; border: 1px solid black;">-->
<!--                --><?php //echo $profile->presentazione_breve?>
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->
<!--    <br>-->
<!--    <table style="width: 100%">-->
<!--        <tr>-->
<!--            <td>Professional presentation</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width: 100%; min-height: 100px; border: 1px solid black;">-->
<!--                --><?php //echo $profile->presentazione_personale?><!-->-->
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->

<!--    <h5><strong>--><?php //echo AmosEen::t('amoseen', 'Aree di interesse') . ': '?><!--</strong></h5>-->
<!--    --><?php //if (!empty(\Yii::$app->getModule('tag'))) { ?>
<!--        <div class="tags-section-sidebar col-xs-12 nop" id="section-tags">-->
<!--            --><?php //echo  \open20\amos\core\forms\ListTagsWidget::widget([
//                'userProfile' => $profile->id,
//                'className' => $profile->className(),
//                'viewFilesCounter' => false,
//                'withTitle' => false,
//                'pageSize' => 10000
//            ]);
//            ?>
<!--        </div>-->
<!--    --><?php //} ?>
    <br>

    <div style="font-style: italic">
        <p  style="text-align: center">
            <?= AmosEen::t('amoseen', '#privacy_een')?>
        </p>
        <br>
        <div class="col-lg-12 col-sm-12">
            <?= AmosEen::t("amoseen", "L'utente ha preso visione dell'informativa sul trattamento dati")?>
        </div>
        <div class="col-lg-12 col-sm-12">
            <?=AmosEen::t('amoseen', 'Il PDF è stato generato automaticamente dalla piattaforma previa autenticazione del richiedente ')?>
        </div>
    </div>
</div>
