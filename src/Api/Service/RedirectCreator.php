<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Wikibase\Api\WikibaseApi;
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
		$params = [
			'from' => $from->__toString(),
			'to' => $to->__toString(),
		];

		$this->api->postRequest( 'wbcreateredirect', $params, $editInfo );
		return true;
	}

}
