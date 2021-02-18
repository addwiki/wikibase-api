<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;

/**
 * @access private
 */
class EntitySearcher {

	private MediawikiApi $api;

	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @return string[] EntityIds
	 */
	public function search( string $entityType, string $string, string $language ): array {
		$params = [
			'search' => $string,
			'language' => $language,
			'type' => $entityType,
		];

		$data = $this->api->getRequest( new SimpleRequest( 'wbsearchentities', $params ) );

		$ids = [];
		foreach ( $data['search'] as $searchResult ) {
			$ids[] = $searchResult['id'];
		}

		return $ids;
	}

}
