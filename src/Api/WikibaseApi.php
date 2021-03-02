<?php

namespace Addwiki\Wikibase\Api;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\Request\SimpleRequest;
use Addwiki\Mediawiki\DataModel\EditInfo;

/**
 * @access private
 */
class WikibaseApi {

	private MediawikiApi $api;

	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param EditInfo|null $editInfo
	 *
	 * @return mixed
	 */
	public function postRequest( string $action, array $params, EditInfo $editInfo = null ) {
		if ( $editInfo !== null ) {
			$params = array_merge( $this->getEditInfoParams( $editInfo ), $params );
		}

		$params['token'] = $this->api->getToken();
		return $this->api->postRequest( new SimpleRequest( $action, $params ) );
	}

	private function getEditInfoParams( EditInfo $editInfo ): array {
		$params = [];

		if ( $editInfo->getSummary() !== '' ) {
			$params['summary'] = $editInfo->getSummary();
		}
		if ( $editInfo->getMinor() ) {
			$params['minor'] = true;
		}
		if ( $editInfo->getBot() ) {
			$params['bot'] = true;
			$params['assert'] = 'bot';
		}
		if ( $editInfo->getMaxlag() ) {
			$params['maxlag'] = $editInfo->getMaxlag();
		}

		return $params;
	}

}
