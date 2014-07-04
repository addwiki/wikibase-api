<?php

namespace Wikibase\Api\Service;

use DataValues\DataValue;
use DataValues\Serializers\DataValueSerializer;
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
	 * @var DataValueSerializer
	 */
	private $dataValueSerializer;

	/**
	 * @param MediawikiApi $api
	 * @param DataValueSerializer $dataValueSerializer
	 */
	public function __construct( MediawikiApi $api, DataValueSerializer $dataValueSerializer ) {
		$this->api = $api;
		$this->dataValueSerializer = $dataValueSerializer;
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
	public function format( DataValue $value, $dataTypeId, GenericOptions $options = null ) {
		if( $options === null ) {
			$options = new GenericOptions();
		}

		$params = array(
			'datavalue' => json_encode( $this->dataValueSerializer->serialize( $value ) ),
			'datatype' => $dataTypeId,
			'options' => json_encode( $options->getOptions() ),
		);

		$result = $this->api->getAction( 'wbformatvalue', $params );
		return $result['result'];
	}

} 