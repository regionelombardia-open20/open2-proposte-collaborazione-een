<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-proposte-collaborazione-een
 * @category   CategoryName
 */

namespace open20\amos\een\commands\controllers;

use open20\amos\attachments\components\FileImport;
use open20\amos\attachments\models\File;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\een\commands\models\CollaborationProposalEen;
use open20\amos\een\models\EenPartnershipProposal;
use open20\amos\een\utility\EenMailUtility;
use open20\amos\tag\models\EntitysTagsMm;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\Json;
use open20\amos\cwh\models\CwhConfigContents;

/**
 * Class ImportController
 * @package open20\amos\een\commands\controllers
 */
class ImportController extends Controller
{
    public static $BOOK_IDS = [
        'markets' => 2,
        'tecnologies' => 3,
        'naces' => 4,
    ];
    public $tempPath;
    public $ContractId;
    public $CountriesForDissemination;
    public $DeadlineDateAfter;
    public $DeadlineDateBefore;
    public $IncludeImages;
    public $OrganisationCountryName;
    public $OrganisationIdentifier;
    public $OrganisationName;
    public $ProfileTypes;
    public $PublishedDateAfter;
    public $PublishedDateBefore;
    public $SubmitDateAfter;
    public $SubmitDateBefore;
    public $UpdateDateAfter;
    public $UpdateDateBefore;
    public $passedOptions = [
        'ContractId',
        'CountriesForDissemination',
        'DeadlineDateAfter',
        'DeadlineDateBefore',
        'IncludeImages',
        'OrganisationCountryName',
        'OrganisationIdentifier',
        'OrganisationName',
        'ProfileTypes',
        'PublishedDateAfter',
        'PublishedDateBefore',
        'SubmitDateAfter',
        'SubmitDateBefore',
        'UpdateDateAfter',
        'UpdateDateBefore',
    ];
    public $passedOptionsExplained = [
        'ContractId' => 'with Contract Id {value}',
        'CountriesForDissemination' => 'for the following countries {value}',
        'DeadlineDateAfter' => 'which expires after {value}',
        'DeadlineDateBefore' => 'which expires before {value}',
        'IncludeImages' => 'with attachments',
        'OrganisationCountryName' => 'published by an organization that resides in {value}',
        'OrganisationIdentifier' => 'published by (identifier) {value}',
        'OrganisationName' => 'published by (name) {value}',
        'ProfileTypes' => 'of these types {value}',
        'PublishedDateAfter' => 'published after {value}',
        'PublishedDateBefore' => 'published before {value}',
        'SubmitDateAfter' => 'submitted after {value}',
        'SubmitDateBefore' => 'submitted before {value}',
        'UpdateDateAfter' => 'updated after {value}',
        'UpdateDateBefore' => 'updated before {value}',
    ];
    
    public function options($actionID)
    {
        return ArrayHelper::merge(parent::options($actionID), $this->passedOptions);
    }
    
    /**
     * Import EEN Profile request proposal from http://een.ec.europa.eu
     *
     *
     * ```
     * yii amos-een/import/start --DeadlineDateAfter="2017-07-07" --ProfileTypes="Tr" --UpdateDateAfter="2017-04-07"
     * ```
     *
     * @throws Exception if the name argument is invalid or nothin passed
     */
    public function actionStart()
    {
        ini_set('memory_limit', '4096M');
        ini_set('soap.wsdl_cache_enabled', '0');
        ini_set('soap.wsdl_cache_ttl', '0');
        
        //ini_set("memory_limit","2G");
        
        if (!count($this->getPassedOptionValues())) {
            throw new Exception('No arguments passed');
        }
        
        $this->ProfileTypes = $this->normalizeArray($this->ProfileTypes);
        $this->CountriesForDissemination = $this->normalizeArray($this->CountriesForDissemination);
        
        $this->DeadlineDateAfter = $this->normalizeDate($this->DeadlineDateAfter);
        $this->DeadlineDateBefore = $this->normalizeDate($this->DeadlineDateBefore);
        $this->PublishedDateAfter = $this->normalizeDate($this->PublishedDateAfter);
        $this->PublishedDateBefore = $this->normalizeDate($this->PublishedDateBefore);
        $this->SubmitDateAfter = $this->normalizeDate($this->SubmitDateAfter);
        $this->SubmitDateBefore = $this->normalizeDate($this->SubmitDateBefore);
        $this->UpdateDateAfter = $this->normalizeDate($this->UpdateDateAfter);
        $this->UpdateDateBefore = $this->normalizeDate($this->UpdateDateBefore);
        
        $this->IncludeImages = $this->normalizeBoolean($this->IncludeImages);
        
        $this->OrganisationCountryName = $this->normalizeString($this->OrganisationCountryName);
        $this->OrganisationIdentifier = $this->normalizeString($this->OrganisationIdentifier);
        $this->OrganisationName = $this->normalizeString($this->OrganisationName);
        
        $this->explainRequest();
        
        $Proposte = CollaborationProposalEen::findAll($this->getPassedOptionValues());
        $proposteCount = count($Proposte);
        if ($proposteCount) {
            foreach ((array)$Proposte->profile as $k => $profile) {
                
                $proposta = EenPartnershipProposal::findOne(['reference_external' => $profile->reference->external]);
                
                $PodProfile = new CollaborationProposalEen([
                    'podXml' => $profile
                ]);

                if (!$proposta) {
                    Console::stdout("NUOVA PROPOSTA {$profile->reference->external} \n\r");
                    $proposta = new EenPartnershipProposal();
                } else {
                    Console::stdout(@$profile->reference->external . "\n\r");
                    Console::stdout("AGGIORNO LA PROPOSTA {$profile->reference->external} \n\r");
                    Console::stdout("CANCELLO I FILE DI {$profile->reference->external} \n\r");
                    File::deleteAll([
                        'attribute' => 'attachments',
                        'model' => EenPartnershipProposal::className(),
                        'itemId' => $proposta['id'],
                    ]);
                }
                
                $proposta->detachBehavior('fileBehavior');
                
                $proposta->setAttributes($PodProfile->toArray());
    
                $proposta->detachBehavior('cwhBehavior');
                $proposta->save(false);
                
                $attachments = $PodProfile->getAttachments();
                
                $attachmentsCount = count($attachments);
                
                if ($attachmentsCount) {
                    Console::stdout("SALVO I FILE {$attachmentsCount} DI {$PodProfile->podXml->reference->external} \n\r");
                    $FileImport = new FileImport();
                    foreach ($PodProfile->getAttachments() as $attachmentFilepath) {
                        Console::stdout("\t\t{$attachmentFilepath}\n\r");
                        $FileImport->importFileForModel($proposta, 'attachments', $attachmentFilepath);
                    }
                }
                
                $tagsNotFound = [];
                $tagsMarkets = $PodProfile->getTagsMarkets();
                $tagsMarketsCount = count($tagsMarkets);
                $tagsNotFound['markets'] = [];
                
                if ($tagsMarketsCount) {
                    Console::stdout("SALVO I TAGS {$tagsMarketsCount} MARKETS DI {$PodProfile->podXml->reference->external} \n\r");
                    
                    $params = [
                        'classname' => EenPartnershipProposal::className(),
                        'record_id' => $proposta['id'],
                        'root_id' => self::$BOOK_IDS['markets']
                    ];
                    
                    EntitysTagsMm::deleteAll($params);
                    
                    foreach ($tagsMarkets as $tag) {
                        Console::stdout("\t\t{$tag['codice']} - {$tag['nome']}");
                        if ($tag->getId()) {
                            Console::stdout("\tTROVATO\n\r");
                            
                            $TagProposta = new EntitysTagsMm(ArrayHelper::merge($params, [
                                'tag_id' => $tag->getId()
                            ]));
                            $TagProposta->save(false);
                        } else {
                            $tagsNotFound['markets'][] = $tag;
                            Console::stdout("\tNON TROVATO\n\r");
                        }
                    }
                }
                
                $tagsNaces = $PodProfile->getTagsNaces();
                $tagsNacesCount = count($tagsNaces);
                $tagsNotFound['naces'] = [];
                
                if ($tagsNacesCount) {
                    Console::stdout("SALVO I TAGS {$tagsNacesCount} NACES DI {$PodProfile->podXml->reference->external} \n\r");
                    
                    $params = [
                        'classname' => EenPartnershipProposal::className(),
                        'record_id' => $proposta['id'],
                        'root_id' => self::$BOOK_IDS['naces']
                    ];
                    
                    EntitysTagsMm::deleteAll($params);
                    
                    foreach ($tagsNaces as $tag) {
                        Console::stdout("\t\t{$tag['codice']} - {$tag['nome']}");
                        if ($tag->getId()) {
                            Console::stdout("\tTROVATO\n\r");
                            
                            $TagProposta = new EntitysTagsMm(ArrayHelper::merge($params, [
                                'tag_id' => $tag->getId()
                            ]));
                            $TagProposta->save(false);
                        } else {
                            $tagsNotFound['naces'][] = $tag;
                            Console::stdout("\tNON TROVATO\n\r");
                        }
                    }
                }
                
                $tagsTechnologies = $PodProfile->getTagsTechnologies();
                
                $tagsTechnologiesCount = count($tagsTechnologies);
                
                if ($tagsTechnologiesCount) {
                    Console::stdout("SALVO I TAGS {$tagsTechnologiesCount} TECHNOLOGIES DI {$PodProfile->podXml->reference->external} \n\r");
                    
                    $params = [
                        'classname' => EenPartnershipProposal::className(),
                        'record_id' => $proposta['id'],
                        'root_id' => self::$BOOK_IDS['tecnologies']
                    ];
                    
                    EntitysTagsMm::deleteAll($params);
                    
                    foreach ($tagsTechnologies as $tag) {
                        Console::stdout("\t\t{$tag['codice']} - {$tag['nome']}");
                        if ($tag->getId()) {
                            Console::stdout("\tTROVATO\n\r");
                            
                            $TagProposta = new EntitysTagsMm(ArrayHelper::merge($params, [
                                'tag_id' => $tag->getId()
                            ]));
                            $TagProposta->save(false);
                        } else {
                            $tagsNotFound['tecnologies'][] = $tag;
                            Console::stdout("\tNON TROVATO\n\r");
                        }
                    }
                }
                
                $proposta->tags_not_found = Json::encode($tagsNotFound, true);
                $proposta->detachBehavior('cwhBehavior');
                $ok = $proposta->save(false);
                
                if ($ok) {
                    //$cwhPubbId = 'een_partnership_proposal-' . $proposta->id;
                    $configContent = CwhConfigContents::findOne(['tablename' => $proposta->tableName()])->id;
                    $cwhPubblicazioni = CwhPubblicazioni::findOne([
                        'cwh_config_contents_id' => $configContent,
                        'content_id' => $proposta->id,
                        'cwh_regole_pubblicazione_id' => 2
                    ]);
                    if (is_null($cwhPubblicazioni)) {
                        $cwhPubblicazioni = new CwhPubblicazioni();
                        $cwhPubblicazioni->cwh_config_contents_id = $configContent;
                        $cwhPubblicazioni->content_id = $proposta->id;
                        $cwhPubblicazioni->cwh_regole_pubblicazione_id = 2;
                    }

                    $ok = $cwhPubblicazioni->save(false);
                    $proposta->saveNotificationSendEmail($proposta->classname(), \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_MAIL, $proposta->id, true);
                    /*if ($ok) {
                        $eenUtility = new EenMailUtility();
                        $eenUtility->sendMails($proposta->id);
                    }*/
                }
                
                Console::stdout("{$profile->reference->external} => ID {$proposta['id']}  \n\r");
                Console::stdout(".................................................................\n\r\n\r");
            }
            Console::stdout($k++ . " TROVATE\n\r");
            
            Console::stdout("Saved!\n\r");
        } else {
            Console::stdout("NESSUNA PROPOSTA TROVATA\n\r");
        }
        
        return [
            'copde' => '1',
            'message' => '??'
        ];
    }
    
    private function normalizeArray($value, $separator = ',')
    {
        if (isset($value)) {
            return explode($separator, $value);
        }
        return null;
    }
    
    private function normalizeDate($date)
    {
        if (isset($date)) {
            return date('Ymd',
                strtotime(
                    date($date)
                )
            );
        }
        return null;
    }
    
    private function normalizeBoolean($value)
    {
        if (isset($value)) {
            if ($value == 1 || $value == '1' || $value == true || $value == 'true' || strtolower($value) == 'y' || strtolower($value) == 'yes') {
                return true;
            }
            return false;
        }
        return null;
    }
    
    private function normalizeString($value)
    {
        if (is_string($value)) {
            return trim($value);
        }
        return null;
    }
    
    private function explainRequest()
    {
        Console::stdout("\n\r\n\r==============================================\n\r\n\r");
        Console::stdout("This request returns EEN Collaboration Proposal:\n\r");
        
        foreach ($this->getPassedOptionValues() as $passedOption => $passedValue) {
            
            $help = $passedOption;
            if (array_key_exists($passedOption, $this->passedOptionsExplained)) {
                $help = $this->passedOptionsExplained[$passedOption];
            }
            
            $value = $passedValue;
            
            if (is_array($passedValue)) {
                $value = implode(',', $passedValue);
            }
            
            Console::stdout(\Yii::t('amoseen', "\t" . $help . "\n\r", [
                'value' => $value
            ]));
        }
        
        Console::stdout("\n\r\n\r==============================================\n\r\n\r");
    }
}
