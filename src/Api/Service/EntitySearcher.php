<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;

/**
 * @access private
 *
 * @author Addshore
 */
class EntitySearcher {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @param \Addwiki\Mediawiki\Api\Client\MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @since 0.8
	 *
	 * @param string $entityType
	 * @param string $string
	 * @param string $language
	 * @return string[] EntityIds
	 */
	public function search( $entityType, $string, $language ) {
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
