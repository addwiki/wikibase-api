<?php

namespace Wikibase\Api;

use DataValues\Deserializers\DataValueDeserializer;
use DataValues\Serializers\DataValueSerializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\Service\AliasGroupSetter;
use Wikibase\Api\Service\ClaimCreator;
use Wikibase\Api\Service\ClaimGetter;
use Wikibase\Api\Service\ClaimRemover;
use Wikibase\Api\Service\ClaimSetter;
use Wikibase\Api\Service\DescriptionSetter;
use Wikibase\Api\Service\ItemMerger;
use Wikibase\Api\Service\LabelSetter;
use Wikibase\Api\Service\ReferenceRemover;
use Wikibase\Api\Service\ReferenceSetter;
use Wikibase\Api\Service\RevisionGetter;
use Wikibase\Api\Service\RevisionSaver;
use Wikibase\Api\Service\RevisionsGetter;
use Wikibase\Api\Service\SiteLinkLinker;
use Wikibase\Api\Service\SiteLinkSetter;
use Wikibase\Api\Service\ValueFormatter;
use Wikibase\Api\Service\ValueParser;
use Wikibase\DataModel\DeserializerFactory;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\SerializerFactory;

/**
 * @author Jeroen De Dauw
 */
class WikibaseFactory {

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
	 * @since 0.1
	 * @return RevisionSaver
	 */
	public function newRevisionSaver() {
		return new RevisionSaver(
			$this->api,
			$this->newDataModelDeserializerFactory()->newEntityDeserializer()
		);
	}

	/**
	 * @since 0.1
	 * @return RevisionGetter
	 */
	public function newRevisionGetter() {
		return new RevisionGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newEntityDeserializer()
		);
	}

	/**
	 * @since 0.4
	 * @return RevisionsGetter
	 */
	public function newRevisionsGetter() {
		return new RevisionsGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newEntityDeserializer()
		);
	}

	/**
	 * @since 0.2
	 * @return ValueParser
	 */
	public function newValueParser() {
		return new ValueParser(
			$this->api,
			$this->newDataValueDeserializer()
		);
	}

	/**
	 * @since 0.2
	 * @return ValueFormatter
	 */
	public function newValueFormatter() {
		return new ValueFormatter(
			$this->api,
			$this->newDataValueSerializer()
		);
	}

	/**
	 * @since 0.2
	 * @return ItemMerger
	 */
	public function newItemMerger() {
		return new ItemMerger( $this->api );
	}

	/**
	 * @since 0.2
	 * @return AliasGroupSetter
	 */
	public function newAliasGroupSetter() {
		return new AliasGroupSetter( $this->api );
	}

	/**
	 * @since 0.2
	 * @return ClaimCreator
	 */
	public function newClaimCreator() {
		return new ClaimCreator(
			$this->api,
			$this->newDataValueSerializer()
		);
	}

	/**
	 * @since 0.2
	 * @return ClaimRemover
	 */
	public function newClaimRemover() {
		return new ClaimRemover( $this->api );
	}

	/**
	 * @since 0.2
	 * @return ClaimSetter
	 */
	public function newClaimSetter() {
		return new ClaimSetter(
			$this->api,
			$this->newDataModelSerializerFactory()->newClaimSerializer()
		);
	}

	/**
	 * @since 0.2
	 * @return DescriptionSetter
	 */
	public function newDescriptionSetter() {
		return new DescriptionSetter( $this->api );
	}

	/**
	 * @since 0.2
	 * @return LabelSetter
	 */
	public function newLabelSetter() {
		return new LabelSetter( $this->api );
	}

	/**
	 * @since 0.2
	 * @return ReferenceRemover
	 */
	public function newReferenceRemover() {
		return new ReferenceRemover( $this->api );
	}

	/**
	 * @since 0.2
	 * @return ReferenceSetter
	 */
	public function newReferenceSetter() {
		return new ReferenceSetter(
			$this->api,
			$this->newDataModelSerializerFactory()->newReferenceSerializer()
		);
	}

	/**
	 * @since 0.2
	 * @return SiteLinkLinker
	 */
	public function newSiteLinkLinker() {
		return new SiteLinkLinker( $this->api );
	}

	/**
	 * @since 0.2
	 * @return SiteLinkSetter
	 */
	public function newSiteLinkSetter() {
		return new SiteLinkSetter( $this->api );
	}

	/**
	 * @since 0.2
	 * @return ClaimGetter
	 */
	public function newClaimGetter() {
		return new ClaimGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newClaimDeserializer()
		);
	}

	private function newDataModelDeserializerFactory() {
		return new DeserializerFactory(
			$this->newDataValueDeserializer(),
			new BasicEntityIdParser()
		);
	}

	private function newDataValueDeserializer() {
		return new DataValueDeserializer( array(
				'number' => 'DataValues\NumberValue',
				'string' => 'DataValues\StringValue',
				'globecoordinate' => 'DataValues\GlobeCoordinateValue',
				'monolingualtext' => 'DataValues\MonolingualTextValue',
				'multilingualtext' => 'DataValues\MultilingualTextValue',
				'quantity' => 'DataValues\QuantityValue',
				'time' => 'DataValues\TimeValue',
				'wikibase-entityid' => 'Wikibase\DataModel\Entity\EntityIdValue', )
		);
	}

	private function newDataValueSerializer() {
		return new DataValueSerializer();
	}

	private function newDataModelSerializerFactory() {
		return new SerializerFactory( new DataValueSerializer() );
	}

}
