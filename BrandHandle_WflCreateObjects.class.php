<?php
/****************************************************************************
   Copyright 2014 LEO
****************************************************************************/

require_once BASEDIR . '/server/interfaces/services/wfl/WflCreateObjects_EnterpriseConnector.class.php';

require_once dirname(__FILE__) . '/config.php';


class BrandHandle_WflCreateObjects extends WflCreateObjects_EnterpriseConnector
{
    final public function getPrio() { 
        return self::PRIO_VERYHIGH; 
    } // VERYLOW, LOW, DEFAULT, HIGH, VERYHIGH
    

    final public function getRunMode()   { 
        return self::RUNMODE_AFTER; 
    }  // BEFORE, AFTER, BEFOREAFTER, OVERRULE

    final public function runBefore (WflCreateObjectsRequest &$req) 
    {

    } 

    final public function runAfter (WflCreateObjectsRequest $req, WflCreateObjectsResponse &$resp) {
        LogHandler::Log("BrandHandleCreateObjects","DEBUG","BrandHandleCreateObjects WflCreateObjects runBefore");
        LogHandler::Log("BrandHandleCreateObjects","DEBUG", print_r($req,true));

        $metaData = $resp->Objects[0]->MetaData;
        $id = $metaData->BasicMetaData->ID;
        $type = $metaData->BasicMetaData->Type;

        foreach($metaData->ExtraMetaData as $extra) {
            if ( $extra->Property == "C_CHANGEAUTOR" ) {
                $changeauthor = $extra->Values[0];

            }
        }
        foreach($metaData->ExtraMetaData as $extra) {
            if ( $extra->Property == "C_RESPONCIBLE" ) {
                $responsible = $extra->Values[0];
            }
        }
        foreach($metaData->ExtraMetaData as $extra) {
            if ( $extra->Property == "C_RESPONCIBLE1" ) {
                $responsibles = $extra->Values[0];
            }
        }
        foreach($metaData->ExtraMetaData as $extra) {
            if ( $extra->Property == "C_MAINAUTOR" ) {
                $mainauthor = $extra->Values[0];
            }
        }
        foreach($metaData->ExtraMetaData as $extra) {
            if ( $extra->Property == "C_MAINAUTOR2" ) {
                $mainauthors = $extra->Values[0];
            }
        }
        if ( $changeauthor == '1' ) {
            foreach($metaData->ExtraMetaData as $extraMetaData) {
                if ($extraMetaData->Property == "C_MAINAUTOR2") {
                    foreach ($extraMetaData->Values as $key => $value) {
                        $newavtor = $newavtor."".$value."/";
                    }
                }
            }
            $newavtor = substr($newavtor, 0, strlen($newavtor)-1);
            $dbDriver=DBDriverFactory::gen();
            $dbobjects=$dbDriver->tablename("objects");
            $sqlecho='update '.$dbobjects.' set `C_MAINAUTOR` = '."'".$newavtor."'".' where `id`='."'".$id."'";
            $sthe=$dbDriver->query($sqlecho);
                
        }
        if ( $type == "Dossier" ) {
            if ( $responsibles != '' AND $responsibles != $mainauthor AND $responsibles != 'Не назначено' ) {
                $param = $responsibles;
            } else {
                $param = $mainauthor;           
            }
            $param = trim($param);
            $child = $metaData->BasicMetaData->ID;
            $dbDriver=DBDriverFactory::gen();
            $dbobjects=$dbDriver->tablename("objects");
            $sqlecho='update '.$dbobjects.' set `C_RESPONCIBLE` = '."'".$param."'".' where `id`='."'".$child."'";   
            $sthe=$dbDriver->query($sqlecho);
        }
        if ( $type == "Article" ) {
            $childid = $resp->Objects[0]->Relations[0]->ParentInfo->ID;
            $dbh  = DBDriverFactory::gen();
            $sql = "select id, C_RESPONCIBLE, C_MAINAUTOR from smart_objects where id = $childid";
            $sth = $dbh->query( $sql );
            $row = $dbh->fetch( $sth );
            $rsform = $row['C_RESPONCIBLE'];
            $child = $resp->Objects[0]->Relations[0]->ChildInfo->ID;
            if ( $rsform != "" AND $responsible == "Не назначено" ) {
                $params = $rsform;
            } else {
                $params = $responsible;
            }
            $params = trim($params);
            $dbDriver=DBDriverFactory::gen();
            $dbobjects=$dbDriver->tablename("objects");
            $sqlecho='update '.$dbobjects.' set `C_RESPONCIBLE` = '."'".$params."'".' where `id`='."'".$child."'";
            $sthe=$dbDriver->query($sqlecho); 
        }
        $objectId = $id;
        require_once BASEDIR . '/server/bizclasses/BizSearch.class.php';
        BizSearch::indexObjectsByIds( array( $objectId ), true, array('Workflow'), true );//require_once BASEDIR.'/server/plugins/SolrSearch/SolrSearch_Search.class.php';
 
    }

final public function runOverruled (WflCreateObjectsRequest $req)   
    {
    } 


}
