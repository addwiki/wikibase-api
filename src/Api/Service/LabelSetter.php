<?php

namespace Wikibase\Api\Service;

use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Term\Term;

/**
 * @author Adam Shorland
 */
class LabelSetter {

	/**
	 * @since 0.2
	 * @param Term $label
	 * @param EntityId|Entity $target
	 */
	public function setLabel( Term $label, $target ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 