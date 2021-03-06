<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Deserializers\Deserializer;
use Wikibase\DataModel\Statement\Statement;

/**
 * @access private
 */
class StatementGetter {

	private ActionApi $api;

	private Deserializer $statementDeserializer;

	public function __construct( ActionApi $api, Deserializer $statementDeserializer ) {
		$this->api = $api;
		$this->statementDeserializer = $statementDeserializer;
	}

	/** @noRector \Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector */
	public function getFromGuid( string $guid ): Statement {
		$params = [
			'claim' => $guid,
		];

		$result = $this->api->request( ActionRequest::simpleGet( 'wbgetclaims', $params ) );
		$arrayShift = array_shift( $result['claims'] );

		$statementSerialization = array_shift( $arrayShift );

		return $this->statementDeserializer->deserialize( $statementSerialization );
	}

}
