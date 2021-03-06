<?php

require_once( dirname(dirname(dirname(dirname(__DIR__)))) . '/maintenance/Maintenance.php' );

class clearConfigs extends Maintenance{
	public function execute() {
		$aFinalPortletList = array();
		$aPortlets = array();

		Hooks::run( 'BSDashboardsUserDashboardPortalPortlets', array( &$aPortlets ) );
		Hooks::run( 'BSDashboardsAdminDashboardPortalPortlets', array( &$aPortlets ) );
		Hooks::run( 'BSDashboardsGetPortlets', array( &$aPortlets ) );

		for ( $i = 0; $i < count( $aPortlets ); $i++ ) {
			$aFinalPortletList[] = $aPortlets[$i]["type"];
		}

		$oDbr = $this->getDB( DB_SLAVE );
		$res = $oDbr->select( 'bs_dashboards_configs', '*' );

		foreach( $res as $row ) {
			$iUser = $row->dc_identifier;
			$sType = $row->dc_type;
			$bHasChange = false;

			try{
				MediaWiki\suppressWarnings();
				$aPortalConfig = unserialize( $row->dc_config ); //backward compatible handling
				MediaWiki\restoreWarnings();
			}catch(Exception $e){
				$this->output( "Object in json only string" );
			}
			if ( $aPortalConfig === FALSE ) { //this should be the normal case
				$aPortalConfig = FormatJson::decode( $row->dc_config );
			} else {
				$aPortalConfig = FormatJson::decode( $aPortalConfig );
				$this->output( "Object in serialized json\n" );
				$bHasChange = true;
			}

			for ( $x = 0; $x < count( $aPortalConfig ); $x++ ){
				for ( $y = 0; $y < count( $aPortalConfig[$x] ); $y++ ){
					if ( !in_array( $aPortalConfig[$x][$y]->type, $aFinalPortletList ) ){
						$this->output( "Will remove " . $aPortalConfig[$x][$y]->type );
						unset( $aPortalConfig[$x][$y] );
						$bHasChange = true;
					}
				}
			}
			$aPortalConfig = FormatJson::encode( $aPortalConfig );
			if ( $bHasChange ) {
				$oDbw = $this->getDB( DB_MASTER );
				$oDbw->update(
						'bs_dashboards_configs',
						array(
							'dc_config' => $aPortalConfig //save json string into db
						),
						array(
							'dc_type' => $sType,
							'dc_identifier' => $iUser
						)
					);
			}
		}
	}
}

$maintClass = 'clearConfigs';
if (defined('RUN_MAINTENANCE_IF_MAIN')) {
	require_once( RUN_MAINTENANCE_IF_MAIN );
} else {
	require_once( DO_MAINTENANCE ); # Make this work on versions before 1.17
}
