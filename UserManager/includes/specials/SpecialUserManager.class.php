<?php

class SpecialUserManager extends BsSpecialPage {

	public function __construct() {
		parent::__construct( 'UserManager', 'usermanager-viewspecialpage' );
	}

	/**
	 *
	 * @global OutputPage $this->getOutput()
	 * @param type $sParameter
	 * @return type
	 */
	public function execute( $sParameter ) {
		parent::execute( $sParameter );
		$this->getOutput()->addModules( 'ext.bluespice.userManager' );
		$this->getOutput()->addHTML( '<div id="bs-usermanager-grid"></div>' );
	}

}
