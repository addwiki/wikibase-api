<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;

/**
 * @access private
 */
class EntitySearcher {

	private ActionApi $api;

	public function __construct( ActionApi $api ) {
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

		$data = $this->api->request( ActionRequest::simpleGet( 'wbsearchentities', $params ) );

		$ids = [];
		foreach ( $data['search'] as $searchResult ) {
			$ids[] = $searchResult['id'];
		}

		return $ids;
	}

}
