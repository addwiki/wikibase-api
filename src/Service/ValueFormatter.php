<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Wikibase\Api\GenericOptions;
use DataValues\DataValue;
use Serializers\Serializer;

/**
 * @access private
 */
class ValueFormatter {

	private ActionApi $api;

	private Serializer $dataValueSerializer;

	public function __construct( ActionApi $api, Serializer $dataValueSerializer ) {
		$this->api = $api;
		$this->dataValueSerializer = $dataValueSerializer;
	}

	/**
	 * @param GenericOptions|null $options
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

		$result = $this->api->request( ActionRequest::simpleGet( 'wbformatvalue', $params ) );
		return $result['result'];
	}

}
