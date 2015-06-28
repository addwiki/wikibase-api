<?php

namespace Wikibase\Api;

use DataValues\Deserializers\DataValueDeserializer;
use DataValues\Serializers\DataValueSerializer;
use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\Service\AliasGroupSetter;
use Wikibase\Api\Service\BadgeIdsGetter;
use Wikibase\Api\Service\ClaimCreator;
use Wikibase\Api\Service\ClaimRemover;
use Wikibase\Api\Service\StatementCreator;
use Wikibase\Api\Service\ClaimGetter;
use Wikibase\Api\Service\ClaimSetter;
use Wikibase\Api\Service\StatementGetter;
use Wikibase\Api\Service\StatementRemover;
use Wikibase\Api\Service\StatementSetter;
use Wikibase\Api\Service\DescriptionSetter;
use Wikibase\Api\Service\ItemMerger;
use Wikibase\Api\Service\LabelSetter;
use Wikibase\Api\Service\RedirectCreator;
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
	 * @since 0.5
	 * @return BadgeIdsGetter
	 */
	public function newBadgeIdsGetter() {
		return new BadgeIdsGetter( $this->api );
	}

	/**
	 * @since 0.5
	 * @return RedirectCreator
	 */
	public function newRedirectCreator() {
		return new RedirectCreator( $this->api );
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

	/**
	 * @since 0.5
	 * @return StatementGetter
	 */
	public function newStatementGetter() {
		return new StatementGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newStatementDeserializer()
		);
	}

	/**
	 * @since 0.3
	 * @deprecated since 0.5, use newStatementGetter
	 * @return ClaimGetter
	 */
	public function newClaimGetter() {
		return $this->newStatementGetter();
	}

	/**
	 * @since 0.5
	 * @return StatementSetter
	 */
	public function newStatementSetter() {
		return new StatementSetter(
			$this->api,
			$this->newDataModelSerializerFactory()->newStatementSerializer()
		);
	}

	/**
	 * @since 0.2
	 * @deprecated since 0.5, use newStatementSetter
	 * @return ClaimSetter
	 */
	public function newClaimSetter() {
		return $this->newStatementSetter();
	}

	/**
	 * @since 0.5
	 * @return StatementCreator
	 */
	public function newStatementCreator() {
		return new StatementCreator(
			$this->api,
			$this->newDataValueSerializer()
		);
	}

	/**
	 * @since 0.2
	 * @deprecated since 0.5, use newStatementCreator
	 * @return ClaimCreator
	 */
	public function newClaimCreator() {
		return $this->newStatementCreator();
	}

	/**
	 * @since 0.5
	 * @return StatementRemover
	 */
	public function newStatementRemover() {
		return new StatementRemover( $this->api );
	}

	/**
	 * @since 0.2
	 * @deprecated since 0.5, use newStatementRemover
	 * @return ClaimRemover
	 */
	public function newClaimRemover() {
		return $this->newStatementRemover();
	}

}
