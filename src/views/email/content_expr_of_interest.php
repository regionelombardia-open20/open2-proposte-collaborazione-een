<?php
use lispa\amos\een\AmosEen;
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\views\email
 * @category   CategoryName
 */

/**
 * @var lispa\amos\een\models\EenExprOfInterest $model
 */

?>
<div style="padding:10px;margin-bottom: 10px;background-color: #ffffff;">

    <div>
        <!-- getImage universal code-->
    </div>

    <!--    <div style="padding:0;margin:0">-->
    <!--        <h3 style="font-size:2em;line-height: 1;margin:0;padding:10px 0;">-->
    <!--            < ?= Html::a($model->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()), ['style' => 'color: #297A38;']) ?>-->
    <!--        </h3>-->
    <!--    </div>-->

    <div style="font-size:13px;font-weight:normal;color:#000000;">

    </div>
    <div style="padding-bottom: 5px;">
        <p>
            <?php
            $titolo = $model->eenPartnershipProposal->content_title;
            $code = $model->eenPartnershipProposal->reference_external;
            $company_name = $model->company_organization;
            $regione = $model->eenNetworkNode->name;
            $orgName = '';
            $emailStaff = '';
            $nomeCognomeStaff  = '';
            $noteUtente = $model->note;

            $staffEen = $model->eenStaff;
            if($staffEen) {
                $nomeCognomeStaff = $staffEen->user->userProfile->nomeCognome;
                $emailStaff = $staffEen->user->email;
                if (!empty($staffEen->user->userProfile->prevalentPartnership)) {
                    $orgName = $staffEen->user->userProfile->prevalentPartnership->name;
                }
            }

            ?>
            <?=AmosEen::t('amoseen', 'Gentile {nomeCognome}', ['nomeCognome' => $model->user->userProfile->nomeCognome])?><br>
            <?php
            $str = '';
         ?>
            <?php if($model->is_request_more_info == 0) {?>
                <?php    if($staffEen) {
                    $str = AmosEen::t('amoseen', ",  <strong>{nomeCognomeStaff}, {orgName} ({emailStaff})</strong> che ha ricevuto copia di questo messaggio. <br>",[
                        'nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'companyName' => $company_name, 'emailStaff' => $emailStaff, 'een_id' => $code
                    ]);
                }?>
                <?=AmosEen::t('amoseen',
                "ti confermiamo che la tua manifestazione di interesse per la proposta di collaborazione EEN dal titolo <strong>{titolo}</strong> e codice <strong>{een_id}</strong> è stata acquisita correttamente.<br>
                                <br>"
                                . "Il tuo caso sarà gestito da un esperto della rete EEN competente per la regione indicata ({regione})"
                                . $str
                                ." <br> Al momento della effettiva presa in carico da parte sua riceverai una notifica dal sistema."
                                . "<br><br>In allegato a questa mail trovi una copia della manifestazione di interesse e l’informativa sulle modalità di trattamento dei dati. 
                                <br><br>
                                <strong>Le tue osservazioni</strong>: {note}
                                <br><br>
                                Cordiali saluti,<br>
                                Lo Staff della Piattaforma Open Innovation
                              ", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'companyName' => $company_name, 'regione' => $regione, 'titolo' => $titolo, 'een_id' => $code, 'note' => $model->note]);?>
            <?php  } else { ?>

                <?php    if($staffEen) {
                    $str = AmosEen::t('amoseen', " che riceve questo messaggio in copia e ti contatterà direttamente:<br>
                     <strong>{nomeCognomeStaff}, {orgName} ({emailStaff})</strong> che ha ricevuto copia di questo messaggio. <br> ",[
                        'nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'companyName' => $company_name, 'emailStaff' => $emailStaff, 'een_id' => $code
                    ]);
                }?>
                <?=AmosEen::t('amoseen',
                    "Ti confermiamo che la tua richiesta di informazioni sui servizi della rete EEN (Enterprise Europe Network) è stata acquisita correttamente.<br>
                                <br>" .
                    "La richiesta di informazioni è stata generata a partire dalla proposta di collaborazione dal titolo <strong>{titolo}</strong> e codice <strong>{een_id}</strong><br><br>"
                    . "La richiesta è stata assegnata ad un esperto della rete EEN competente per la regione indicata ({regione} )"
                    . $str
                    ." <br> Al momento della effettiva presa in carico da parte sua riceverai una notifica dal sistema."
                    . "<br><br>
                     <strong>Le tue osservazioni</strong>: {note}
                     <br><br>
                                Cordiali saluti,<br>
                                Lo Staff della Piattaforma Open Innovation
                              ", ['nomeCognomeStaff' => $nomeCognomeStaff, 'orgName' => $orgName, 'companyName' => $company_name, 'regione' => $regione, 'titolo' => $titolo, 'een_id' => $code, 'note' => $model->note]);?>
            <?php  } ?>
        </p>

    </div>
</div>