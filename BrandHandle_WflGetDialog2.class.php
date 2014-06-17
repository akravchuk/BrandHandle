<?php

/**
 * @package 	HandleGetDialog plug-in for Enterprise
 * @since 	    v6.1
 * @copyright	LEO. All Rights Reserved.
 */

require_once BASEDIR.'/server/interfaces/services/wfl/WflGetDialog2_EnterpriseConnector.class.php';

class BrandHandle_WflGetDialog2  extends WflGetDialog2_EnterpriseConnector
{
	final public function getPrio()      { return self::PRIO_VERYHIGH; }       // VERYLOW, LOW, DEFAULT, HIGH, VERYHIGH
	final public function getRunMode()   { return self::RUNMODE_AFTER; }  // BEFORE, AFTER, BEFOREAFTER, OVERRULE

	final public function runBefore( WflGetDialog2Request &$req ){}

	final public function runAfter( WflGetDialog2Request $req, WflGetDialog2Response &$resp ) {
		foreach ($req->MetaData as $metadata) {
	  		if ($metadata->Property == 'Publication') {
	  		    $section = $metadata->PropertyValues[0]->Value;
	  		    if ( $section != '' && $section != null ) {
				    self::setKeyWordsForSectionAutor( $resp, $section);
					self::setKeyWordsForSectionAutors( $resp, $section);
					self::setKeyWordsForSectionRespOne( $resp, $section);
					self::setKeyWordsForSectionsResp( $resp, $section);
					
				}
		    break;
	  		}
		}
	}

	final public function runOverruled( WflGetDialog2Request $req )
	{
		  //LogHandler::Log('HandleGetDialog', 'DEBUG',"'Overrule' event called for WflGetDialog");	
	}
	
	
	function setKeyWordsForSectionAutor( &$resp, $section) {

		require_once dirname(__FILE__) . '/config.php';
		$mapping = unserialize( HGD_CATEGORYMAPPING_AUTOR );
		$dbh  = DBDriverFactory::gen();
		$sql = "select publication from smart_publications where id = $section";
		$sth = $dbh->query( $sql );
		$row = $dbh->fetch( $sth );
		$sectionname = $row['publication'];
		$tabs = $resp->Dialog->Tabs;
		foreach ( $resp->Dialog->Tabs as $Tab) {
			$NewWidget = array();
			foreach( $Tab->Widgets as $Widget) {
				if ( strtolower($Widget->PropertyInfo->Name) == strtolower(HGD_AUTOR)) {
			        if (array_key_exists( $sectionname, $mapping )) {
			        	$Widget->PropertyInfo->ValueList = explode(',',$mapping[$sectionname]) ;    		
			        }
				}
			}
		}
    }
	
	function setKeyWordsForSectionAutors( &$resp, $section) { 
		require_once dirname(__FILE__) . '/config.php';
		$mapping = unserialize( HGD_CATEGORYMAPPING_AUTORS );
		$dbh  = DBDriverFactory::gen();
		$sql = "select publication from smart_publications where id = $section";
		$sth = $dbh->query( $sql );
		$row = $dbh->fetch( $sth );
		$sectionname = $row['publication'];
		$tabs = $resp->Dialog->Tabs;
        foreach ( $resp->Dialog->Tabs as $Tab) {
			$NewWidget = array();
			LogHandler::Log('-HandleGetDialog', 'DEBUG',"Title:" . $Tab->Title );
			foreach( $Tab->Widgets as $Widget) {
				if ( strtolower($Widget->PropertyInfo->Name) == strtolower(HGD_AUTORS)) {       
	                if (array_key_exists( $sectionname, $mapping )) {
	                        $Widget->PropertyInfo->ValueList = explode(',',$mapping[$sectionname]) ;   		
	                }
                }
		    } 
		} 
    }	
	
	function setKeyWordsForSectionRespOne( &$resp, $section) {
		require_once dirname(__FILE__) . '/config.php';
		$mapping = unserialize( HGD_CATEGORYMAPPING1 );
		$dbh  = DBDriverFactory::gen();
	    $sql = "select publication from smart_publications where id = $section";
	    $sth = $dbh->query( $sql );
	    $row = $dbh->fetch( $sth );
	    $sectionname = $row['publication'];
		// walk trough the dialog tabs
	    $tabs = $resp->Dialog->Tabs;
	    foreach ( $resp->Dialog->Tabs as $Tab) {
			$NewWidget = array();
			//LogHandler::Log('-HandleGetDialog', 'DEBUG',"Title:" . $Tab->Title );	
			foreach( $Tab->Widgets as $Widget) {						
			    //LogHandler::Log('-HandleGetDialog', 'DEBUG',"Field name:" . $Widget->PropertyInfo->Name );
			    if ( strtolower($Widget->PropertyInfo->Name) == strtolower(HGD_RESPONSIBLES)) {
			        //LogHandler::Log('-HandleGetDialog', 'DEBUG',"Field [" . HGD_RESPONSIBLE1 . "] found for section [$sectionname]");
			        if (array_key_exists( $sectionname, $mapping )) {
			            $Widget->PropertyInfo->ValueList = explode(',',$mapping[$sectionname]) ;  
			        }
			        //LogHandler::Log('-HandleGetDialog', 'DEBUG',"ValueList:" . print_r( $Widget->PropertyInfo->ValueList,1));       
			    }  
			}  
		} 
    }


	function setKeyWordsForSectionsResp( &$resp, $section) {
		require_once dirname(__FILE__) . '/config.php';
		$mapping = unserialize( HGD_CATEGORYMAPPING ); 
		$dbh  = DBDriverFactory::gen();
	    $sql = "select publication from smart_publications where id = $section";
		//LogHandler::Log('HandleGetDialog', 'DEBUG',"sql = $sql");
	    $sth = $dbh->query( $sql );
	    $row = $dbh->fetch( $sth );
	    $sectionname = $row['publication'];
	    // walk trough the dialog tabs
	    $tabs = $resp->Dialog->Tabs;
	    foreach ( $resp->Dialog->Tabs as $Tab) {
			$NewWidget = array();
			//LogHandler::Log('-HandleGetDialog', 'DEBUG',"Title:" . $Tab->Title );	
			foreach( $Tab->Widgets as $Widget) {						
			    //LogHandler::Log('-HandleGetDialog', 'DEBUG',"Field name:" . $Widget->PropertyInfo->Name );
			    if ( strtolower($Widget->PropertyInfo->Name) == strtolower(HGD_RESPONSIBLE)) {
			        //LogHandler::Log('-HandleGetDialog', 'DEBUG',"Field [" . HGD_RESPONSIBLE1 . "] found for section [$sectionname]");
			        if (array_key_exists( $sectionname, $mapping )) {
			            $Widget->PropertyInfo->ValueList = explode(',',$mapping[$sectionname]) ;
			        }
			        //LogHandler::Log('-HandleGetDialog', 'DEBUG',"ValueList:" . print_r( $Widget->PropertyInfo->ValueList,1));       
			    }  
			}  
		} 
	}	
}
?>