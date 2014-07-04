<?php


namespace Wikibase\Api\Service;

use DataValues\Deserializers\DataValueDeserializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\DataModel\Deserializers\ClaimDeserializer;
use Wikibase\DataModel\Deserializers\EntityIdDeserializer;
use Wikibase\DataModel\Deserializers\ReferenceDeserializer;
use Wikibase\DataModel\Deserializers\ReferenceListDeserializer;
use Wikibase\DataModel\Deserializers\SnakDeserializer;
use Wikibase\DataModel\Deserializers\SnakListDeserializer;
use Wikibase\DataModel\Entity\BasicEntityIdParser;

/**
 * @author Adam Shorland
 */
class ClaimGetter {

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
	 * @param string $guid
	 *
	 * @return bool
	 */
	public function getFromGuid( $guid ) {
		$params = array(
			'claim' => $guid,
		);

		$result = $this->api->getAction( 'wbgetclaims', $params );

		$claimSerialization = array_shift( array_shift( $result['claims'] ) );

		//TODO inject me
		$snakDeserializer = new SnakDeserializer(
			new DataValueDeserializer(),
			new EntityIdDeserializer( new BasicEntityIdParser() )
		);
		//TODO inject me
		$claimDeserializer = new ClaimDeserializer(
			$snakDeserializer,
			new SnakListDeserializer( $snakDeserializer ),
			new ReferenceListDeserializer( new ReferenceDeserializer( $snakDeserializer ) )
		);

		return $claimDeserializer->deserialize( $claimSerialization );
	}

} 