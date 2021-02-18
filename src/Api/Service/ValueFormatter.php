<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Mediawiki\Api\Client\SimpleRequest;
use Addwiki\Wikibase\Api\GenericOptions;
use DataValues\DataValue;
use Serializers\Serializer;

/**
 * @access private
 *
 * @author Addshore
 */
class ValueFormatter {

	private MediawikiApi $api;

	private \Serializers\Serializer $dataValueSerializer;

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
	 * @param GenericOptions|null $options
	 *
	 */
	public function format( DataValue $value, string $dataTypeId, GenericOptions $options = null ): string {
		if ( $options === null ) {
			$options = new GenericOptions();
		}

		$params = [
			'datavalue' => json_encode( $this->dataValueSerializer->serialize( $value ) ),
			'datatype' => $dataTypeId,
			'options' => json_encode( $options->getOptions() ),
		];

		$result = $this->api->getRequest( new SimpleRequest( 'wbformatvalue', $params ) );
		return $result['result'];
	}

}
