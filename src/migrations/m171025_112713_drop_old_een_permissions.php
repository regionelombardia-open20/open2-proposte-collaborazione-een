<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m171025_112713_drop_old_een_permissions
 */
class m171025_112713_drop_old_een_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }
    
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setRolesRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }
    
    private function setRolesRoles()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_PROP_COLLAB_EEN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo amministratore per il plugin proposte collaborazione een',
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'LETTORE_PROP_COLLAB_EEN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo lettore per il plugin proposte collaborazione een',
            ]
        ];
    }
    
    private function setModelPermissions()
    {
        return [
            
            // Permessi per il model ProposteCollaborazioneEen
            [
                'name' => 'PROPOSTECOLLABORAZIONEEEN_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di creazione per il model ProposteCollaborazioneEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'PROPOSTECOLLABORAZIONEEEN_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di lettura per il model ProposteCollaborazioneEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN', 'LETTORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'PROPOSTECOLLABORAZIONEEEN_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica per il model ProposteCollaborazioneEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'PROPOSTECOLLABORAZIONEEEN_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellazione per il model ProposteCollaborazioneEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            
            // Permessi per il model TipologiaProposteEen
            [
                'name' => 'TIPOLOGIAPROPOSTEEEN_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di creazione per il model TipologiaProposteEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'TIPOLOGIAPROPOSTEEEN_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di lettura per il model TipologiaProposteEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN', 'LETTORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'TIPOLOGIAPROPOSTEEEN_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica per il model TipologiaProposteEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'TIPOLOGIAPROPOSTEEEN_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellazione per il model TipologiaProposteEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            
            // Permessi per il model ProposteCollaborazioneEenTipologiaProposteEenMm
            [
                'name' => 'PROPOSTECOLLABORAZIONEEENTIPOLOGIAPROPOSTEEENMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di creazione per il model ProposteCollaborazioneEenTipologiaProposteEenMm',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'PROPOSTECOLLABORAZIONEEENTIPOLOGIAPROPOSTEEENMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di lettura per il model ProposteCollaborazioneEenTipologiaProposteEenMm',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN', 'LETTORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'PROPOSTECOLLABORAZIONEEENTIPOLOGIAPROPOSTEEENMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modifica per il model ProposteCollaborazioneEenTipologiaProposteEenMm',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'PROPOSTECOLLABORAZIONEEENTIPOLOGIAPROPOSTEEENMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellazione per il model ProposteCollaborazioneEenTipologiaProposteEenMm',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ]
        ];
    }
    
    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permesso per la dashboard per il widget ';
        return [
            [
                'name' => 'open20\amos\proposte_collaborazione_een\widgets\icons\WidgetIconProposteCollaborazioneEenDashboard',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconProposteCollaborazioneEenDashboard',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'open20\amos\proposte_collaborazione_een\widgets\icons\WidgetIconTipologiaProposteEen',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconTipologiaProposteEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN']
            ],
            [
                'name' => 'open20\amos\proposte_collaborazione_een\widgets\icons\WidgetIconProposteCollaborazioneEen',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconProposteCollaborazioneEen',
                'parent' => ['AMMINISTRATORE_PROP_COLLAB_EEN', 'LETTORE_PROP_COLLAB_EEN']
            ]
        ];
    }
}
