<?php

class PageAssignmentsUsersAdditionalPermissionsHooks {
	private static $sTempGroup = 'oOAsSiGnEdUsErOo';
	protected static $aAssignedUserForTitle = array();

	/**
	 *
	 * @param Title $title
	 * @param User $user
	 * @param string $action
	 * @param boolean $result
	 * @return boolean
	 */
	public static function onUserCan( &$title, &$user, $action, &$result ) {
		self::addAdditionalPermissions( $title, $user );
		return true;
	}

	/**
	 * Hook handler for FlaggedRevs RevisionReview overwrite.
	 * ATTENTION: This is a handler for a custom hook in FlaggedRevsConnector!
	 * It will be removed in next version!
	 * @param FRCRevisionReview $oRevisionReview
	 * @param Title $oTitle
	 * @param type $aArgs
	 * @return boolean
	 * @deprecated since version 1.22
	 */
	public static function onRevisionAjaxReviewBeforeParams( $oRevisionReview, &$oTitle, &$aArgs ) {
		//MW BeforeInitialize hook is not present in ajax calls, so apply
		//possible permissions for responsible editors in this context
		if( is_null($oTitle) ) {
			foreach( $aArgs as $sArg ) {
				$set = explode( '|', $sArg, 2 );
				if( count( $set ) != 2 ) {
					continue;
				}

				list( $sKey, $vVal ) = $set;
				if( $sKey != 'target' ) {
					continue;
				}

				$oTitle = Title::newFromURL( $vVal );
				break;
			}
		}
		self::addAdditionalPermissions(
			$oTitle,
			RequestContext::getMain()->getUser()
		);

		return true;
	}

	public static function addAdditionalPermissions( $oTitle, $oUser ) {
		$aPermissions = BsConfig::get(
			'MW::AssignedUsersAdditionalPermissions::Permissions'
		);
		if( empty($aPermissions) ) {
			return true;
		}
		if( !self::isAssignableUser($oUser) ) {
			return false;
		}
		if( !self::isAssignableTitle($oTitle) ) {
			return false;
		}
		if( self::isTempGroupAppliedToUser($oUser) ) {
			return true;
		}
		if( !self::isUserAssigned($oTitle, $oUser) ) {
			return true;
		}

		BsGroupHelper::addPermissionsToGroup(
			self::$sTempGroup,
			$aPermissions,
			array( $oTitle->getNamespace() )
		);

		BsGroupHelper::addTempGroupToUser( $oUser, self::$sTempGroup );

		return true;
	}

	protected static function isUserAssigned( Title $oTitle, User $oUser ) {
		if( array_key_exists($oUser->getId(), self::$aAssignedUserForTitle) ) {
			if( array_key_exists($oTitle->getArticleID(), self::$aAssignedUserForTitle[$oUser->getId()]) ) {
				return self::$aAssignedUserForTitle[$oUser->getId()][$oTitle->getArticleID()];
			}
		}
		$oRes = wfGetDB( DB_SLAVE )->selectRow(
			'bs_pageassignments',
			'*',
			array(
				'pa_page_id' => $oTitle->getArticleID(),
				'pa_assignee_type' => 'user',
				'pa_assignee_key' => $oUser->getName(),
			),
			__METHOD__
		);
		if( $oRes ) {
			self::$aAssignedUserForTitle[$oUser->getId()][$oTitle->getArticleID()] = true;
			return true;
		}

		if( empty( $oUser->getEffectiveGroups() ) ) {
			return false;
		}

		$oRes = wfGetDB( DB_SLAVE )->selectRow(
			'bs_pageassignments',
			'*',
			array(
				'pa_page_id' => $oTitle->getArticleID(),
				'pa_assignee_type' => 'group',
				'pa_assignee_key' => $oUser->getEffectiveGroups(),
			),
			__METHOD__
		);

		if( $oRes ) {
			self::$aAssignedUserForTitle[$oUser->getId()][$oTitle->getArticleID()] = true;
			return true;
		}

		self::$aAssignedUserForTitle[$oUser->getId()][$oTitle->getArticleID()] = false;
		return false;
	}

	protected static function isAssignableUser( $oUser ) {
		if( !$oUser instanceof User ) {
			return false;
		}
		if( $oUser->isAnon() ) {
			return false;
		}
		//for now, we only care about the current user
		if( $oUser->getId() != RequestContext::getMain()->getUser()->getId() ) {
			return false;
		}
		return true;
	}

	protected static function isTempGroupAppliedToUser( User $oUser ) {
		if( in_array(self::$sTempGroup, $oUser->getGroups()) ) {
			return true;
		}
		return false;
	}

	protected static function isAssignableTitle( $oTitle ) {
		if( !$oTitle instanceof Title ) {
			return false;
		}
		if( $oTitle->isSpecialPage() ) {
			return false;
		}
		return true;
	}

	/**
	 * Never ever save this group to any user!
	 * @param User $user
	 * @param string $group
	 * @return boolean
	 */
	public static function onUserAddGroup( $user, &$group ) {
		if( self::$sTempGroup !== $group ) {
			return true;
		}
		return false;
	}
}