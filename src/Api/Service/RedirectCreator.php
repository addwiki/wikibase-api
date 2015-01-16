<?php

namespace Wikibase\Api\Service;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Wikibase\DataModel\Entity\EntityId;

/**
 * @author Adam Shorland
 */
class RedirectCreator {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param EntityId $from
	 * @param EntityId $to
	 *
	 * @return bool
	 */
	public function create( EntityId $from, EntityId $to ) {
		$this->api->postRequest( new SimpleRequest(
			'wbcreateredirect',
			array(
				'token' => $this->api->getToken(),
				'from' => $from->__toString(),
				'to' => $to->__toString(),
			)
		) );
		return true;
	}

} 