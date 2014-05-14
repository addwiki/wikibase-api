<?php

namespace Wikibase\Api\Service;

use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Term\AliasGroupList;

/**
 * @author Adam Shorland
 */
class AliasGroupListSetter {

	/**
	 * @param AliasGroupList $description
	 * @param EntityId|Entity $target
	 */
	public function setAliasGroupList( AliasGroupList $aliasGroupList, $target ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 