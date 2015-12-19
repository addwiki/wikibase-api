<?php


namespace Wikibase\Api;

use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\EditInfo;

/**
 * @access private
 *
 * @author Bene* < benestar.wikimedia@gmail.com >
 */
class WikibaseApi {

	/**
	 * @var MediawikiApi $api
	 */
	private $api;

	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
	}

	/**
	 * @param string $action
	 * @param array $params
	 * @param EditInfo|null $editInfo
	 *
	 * @return mixed
	 */
	public function postRequest( $action, array $params, EditInfo $editInfo = null ) {
		if ( $editInfo !== null ) {
			$params = array_merge( $this->getEditInfoParams( $editInfo ), $params );
		}

		$params['token'] = $this->api->getToken();
		return $this->api->postRequest( new SimpleRequest( $action, $params ) );
	}

	private function getEditInfoParams( EditInfo $editInfo ) {
		$params = array();

		if ( $editInfo->getSummary() ) {
			$params['summary'] = $editInfo->getSummary();
		}
		if ( $editInfo->getMinor() ) {
			$params['minor'] = true;
		}
		if ( $editInfo->getBot() ) {
			$params['bot'] = true;
			$params['assert'] = 'bot';
		}

		return $params;
	}

}
