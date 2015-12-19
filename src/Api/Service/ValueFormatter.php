<?php

namespace Wikibase\Api\Service;

use DataValues\DataValue;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Serializers\Serializer;
use Wikibase\Api\GenericOptions;

/**
 * @access private
 *
 * @author Addshore
 */
class ValueFormatter {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var Serializer
	 */
	private $dataValueSerializer;

	/**
	 * @param MediawikiApi $api
	 * @param Serializer $dataValueSerializer
	 */
	public function __construct( MediawikiApi $api, Serializer $dataValueSerializer ) {
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

		$result = $this->api->getRequest( new SimpleRequest( 'wbformatvalue', $params ) );
		return $result['result'];
	}

} 