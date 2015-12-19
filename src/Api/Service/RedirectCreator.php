<?php

namespace Wikibase\Api\Service;

use Mediawiki\DataModel\EditInfo;
use Wikibase\Api\WikibaseApi;
use Wikibase\DataModel\Entity\EntityId;

/**
 * @access private
 *
 * @author Addshore
 */
class RedirectCreator {

	/**
	 * @var WikibaseApi
	 */
	private $api;

	/**
	 * @param WikibaseApi $api
	 */
	public function __construct( WikibaseApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param EntityId $from
	 * @param EntityId $to
	 * @param EditInfo|null $editInfo
	 *
	 * @return bool
	 */
	public function create( EntityId $from, EntityId $to, EditInfo $editInfo = null ) {
		$params = array(
			'from' => $from->__toString(),
			'to' => $to->__toString(),
		);

		$this->api->postRequest( 'wbcreateredirect', $params, $editInfo );
		return true;
	}

} 