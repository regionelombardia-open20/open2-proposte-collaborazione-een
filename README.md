# Een Partnership Proposal 

Questo documento specifica in dettaglio lo script di importazione een

## Configure Tag
insert  in backend/config/main 
```php

	   if (isset($modules['een'])) {
        $modules['tag']['modelsEnabled'][] = 'open20\amos\een\models\EenPartnershipProposal';
    }
```
and enable the technlogic tags  on the plugin configuration tags

## Configure Comments
Insert in backend/config/modules-amos
```php

 'comments' => [
       'class' => 'open20\amos\comments\AmosComments',
       'modelsEnabled' => [
       	   'open20\amos\een\models\EenExprOfInterest'
        ]
   ]
```

## Import EEN (Console command)
Lo script di importazione è in grado di gestire la risposta del WS soap, mappando tutti le proprietà ritornate per ogni proposta (profile) gestendono:
- Campi distintivi,
- Allegati,
- Keyword (tags);

Lo script una volta avviato scrive su un log (@console/runtime/een/call) questi dati:
- **Allegati** per ogni proposta (vengono poi spostati sull'amos-attachments)
- **Request XML** utile per poter replicare la chiamata con SaopUI
- **Response XML** utile per poter capire se i dati ritornati sono corretti
- **Tag Non trovati** visto che i tag delle EEN non sono interrogabili e non hanno un id univoco, ma un mero codice posizionale, vengono tracciati tutti i tag non trovati e salvati su una colonna *tags_not_found* sulla proposta
- **Output parlante** vengono indicate infomazioni parlanti, quali:
    - Spiegazione della Request
    - Info su allegati
    - Info sui tags
    - Info sulla proposta
    
> Si consiglia di eliminare il contenuto della cartella **@console/runtime/een/call** ogni X chiamate (oppure effettuare un rotate)

Inoltre
> Si consiglia di salvare l'output dello script all'interno di un file di log.

###Base route
  Command | Note | 
  --- | --- |
  yii amos-een/import/start  | Importa le proposte di collaborazione |


###Options 
Le options sono i parametri che il WDSL può gestire.

  Option | Note |  Esempio
  --- | --- | ---   |
  --Username  | Username  | Già impostato nell'env |
  --Password  | Password | Già impostato nell'env |
  --ContractId  | Id contratto  | Stringa: Mai utilizzato  |
  --CountriesForDissemination  | Diffusa negli stati  | Array:  Mai utilizzato  |
  --DeadlineDateAfter  | Scadenza dopo la data  | Stringa: AAAAMMDD |
  --DeadlineDateBefore  | Scadenza prima la data  | Stringa: AAAAMMDD |
  --IncludeImages  | Includere allegati  | Boolean: 1/true |
  --OrganisationCountryName  | ?  |  |
  --OrganisationIdentifier  |  ?  |  |
  --OrganisationName  |   ?  |  |
  --ProfileTypes  | Tipologia di EEN |Array: Tr, To, Br, Bo, Rdr  |
  --PublishedDateAfter  |Pubblicata dopo la data  | Stringa: AAAAMMDD |
  --PublishedDateBefore  |Pubblicata prima la data  | Stringa: AAAAMMDD |
  --SubmitDateAfter  |Inviata dopo la data  | Stringa: AAAAMMDD |
  --SubmitDateBefore  |Inviata prima la data  | Stringa: AAAAMMDD |
  --UpdateDateAfter  |Aggiornata dopo la data  | Stringa: AAAAMMDD |
  --UpdateDateBefore  |Inviata prima la data  | Stringa: AAAAMMDD |
  
###Esempi di chiamate

*Proposte di collaborazione EEN di tipo Tr con data di scadenza dopo il 2017-07-07(Compreso)*

    php yii amos-een/import/start --DeadlineDateAfter="2017-07-07" --ProfileTypes="Tr"


*Proposte di collaborazione EEN di tipo Br aggiornate dopo il 2017-11-01 (Compreso) , con allegati*

    php yii amos-een/import/start --UpdateDateAfter="2017-11-01" --ProfileTypes="Tr" --IncludeImages=1
    
    
*Proposte di collaborazione EEN di tipo Tr,To,Br,Bo,Rdr aggiornate dopo il 2017-11-01 (Compreso)*

    php yii amos-een/import/start --UpdateDateAfter="2017-11-01" --ProfileTypes="Tr,To,Br,Bo,Rdr"
    
    
###Web Service
è possibile recuperare le proposte di collaborazione EEN tramite WS.

Per poter accedere al servizio è necessario:
- essere utente a sistema
- avere associato il permesso *EEN_ENABLE_READ_WS*

Il sistema di autenticazione è *BASIC AUTHENTICATION* perciò **username** e **password** saranno sempre presenti nella chiamata

ulteriore parametro necessario è **date** nel fomato *Y-m-d*

URL esempio 

    #DOMINIO#/een/api/get-een?date=2018-07-01

Saranno restituite tutte le proposte EEN la cui ***data di ultimo aggiornamento** cadrà nell'intervallo tra la **data ricevuta** tramite parametro *date* e **7 giorni** che lo precedono
