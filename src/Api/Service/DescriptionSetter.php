<?php

namespace Wikibase\Api\Service;

use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Term\Term;

/**
 * @author Adam Shorland
 */
class DescriptionSetter {

	/**
	 * @since 0.2
	 * @param Term $description
	 * @param EntityId|Entity $target
	 */
	public function setDescription( Term $description, $target ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 