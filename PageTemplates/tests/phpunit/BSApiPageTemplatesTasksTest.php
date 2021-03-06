<?php

/**
 * @group medium
 * @group API
 * @group BlueSpice
 * @group BlueSpiceExtensions
 * @group BlueSpicePageTemplates
 */
class BSApiPageTemplatesTasksTest extends BSApiTasksTestBase {

	protected function getModuleName( ) {
		return 'bs-pagetemplates-tasks';
	}

	function getTokens() {
		return $this->getTokenList( self::$users[ 'sysop' ] );
	}

	public function testDoEditTemplate() {
		//add template
		$oData = $this->executeTask(
			'doEditTemplate',
			array(
				'desc' => 'Dummy template',
				'label' => 'Dummy 1',
				'template' => 'Dummy 1 title',
				'targetns' => NS_FILE
			)
		);

		$this->assertTrue( $oData->success );

		$this->assertSelect(
			'bs_pagetemplate',
			array( 'pt_id', 'pt_template_title', 'pt_target_namespace'  ),
			array( "pt_label = 'Dummy 1'" ),
			array(  array( 9, 'Dummy 1 title', 6 )  )
		);

		$iIDAdded = 9;

		//edit template
		$oData = $this->executeTask(
			'doEditTemplate',
			array(
				'id' => $iIDAdded,
				'desc' => 'Faux template',
				'label' => 'Faux 1',
				'template' => 'Faux 1 title',
				'targetns' => NS_MAIN
			)
		);

		$this->assertTrue( $oData->success );

		$this->assertSelect(
			'bs_pagetemplate',
			array( 'pt_template_title', 'pt_target_namespace'  ),
			array( 'pt_id = 9' ),
			array(  array( 'Faux 1 title', 0 )  )
		);
	}

	public function testDoDeleteTemplates() {

		$aIDsToDelete = array(
			1 => 'Test_01',
			9 => 'Faux 1 title'
		);

		foreach( $aIDsToDelete as $iID => $sTitle ) {
			$this->assertFalse( $this->isDeleted( $iID ) );
		}

		$oData = $this->executeTask(
			'doDeleteTemplates',
			array(
				'ids' => $aIDsToDelete
			)
		);

		$this->assertTrue( $oData->success );

		foreach( $aIDsToDelete as $iID => $sTitle ) {
			$this->assertTrue( $this->isDeleted( $iID ) );
		}
	}

	protected function isDeleted( $iID ) {
		$db = wfGetDB( DB_SLAVE );
		$res = $db->select( 'bs_pagetemplate', array( 'pt_id' ), array( 'pt_id = ' . $iID ), wfGetCaller() );
		if( $res->numRows() === 0 ) {
			return true;
		}

		return false;
	}
}
