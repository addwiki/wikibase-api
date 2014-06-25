<?php

namespace Wikibase\Api\Service;

use DataValues\DataValue;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\GenericOptions;

/**
 * @author Adam Shorland
 */
class ValueFormatter {

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
	 * @since 0.2
	 *
	 * @param DataValue $value
	 * @param string $dataTypeId
	 * @param GenericOptions $options
	 *
	 * @returns string
	 */
	public function format( DataValue $value, $dataTypeId, GenericOptions $options ) {
		//TODO implement me
		throw new \BadMethodCallException( 'Not yet implemented' );
	}

} 