<?php

namespace Wikibase\Api;

use Deserializers\Deserializer;
use Mediawiki\Api\MediawikiApi;
use Serializers\Serializer;
use Wikibase\Api\Lookup\EntityApiLookup;
use Wikibase\Api\Lookup\ItemApiLookup;
use Wikibase\Api\Lookup\PropertyApiLookup;
use Wikibase\Api\Service\AliasGroupSetter;
use Wikibase\Api\Service\BadgeIdsGetter;
use Wikibase\Api\Service\DescriptionSetter;
use Wikibase\Api\Service\EntityDocumentSaver;
use Wikibase\Api\Service\EntitySearcher;
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
use Wikibase\Api\Service\StatementCreator;
use Wikibase\Api\Service\StatementGetter;
use Wikibase\Api\Service\StatementRemover;
use Wikibase\Api\Service\StatementSetter;
use Wikibase\Api\Service\ValueFormatter;
use Wikibase\Api\Service\ValueParser;
use Wikibase\DataModel\DeserializerFactory;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\SerializerFactory;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\EntityRetrievingTermLookup;
use Wikibase\DataModel\Services\Lookup\ItemLookup;
use Wikibase\DataModel\Services\Lookup\PropertyLookup;
use Wikibase\DataModel\Services\Lookup\TermLookup;

/**
 * @author Addshore
 *
 * @access public
 */
class WikibaseFactory {

	/**
	 * @var MediawikiApi
	 */
	private $api;

	/**
	 * @var Deserializer
	 */
	private $dataValueDeserializer;

	/**
	 * @var Serializer
	 */
	private $dataValueSerializer;

	/**
	 * @param MediawikiApi $api
	 * @param Deserializer $dvDeserializer
	 * @param Serializer $dvSerializer
	 */
	public function __construct( MediawikiApi $api, Deserializer $dvDeserializer, Serializer $dvSerializer ) {
		$this->api = $api;
		$this->dataValueDeserializer = $dvDeserializer;
		$this->dataValueSerializer = $dvSerializer;
	}

	/**
	 * @since 0.1
	 * @return RevisionSaver
	 */
	public function newRevisionSaver() {
		return new RevisionSaver(
			$this->newWikibaseApi(),
			$this->newDataModelDeserializerFactory()->newEntityDeserializer(),
			$this->newDataModelSerializerFactory()->newEntitySerializer()
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
			$this->dataValueDeserializer
		);
	}

	/**
	 * @since 0.2
	 * @return ValueFormatter
	 */
	public function newValueFormatter() {
		return new ValueFormatter(
			$this->api,
			$this->dataValueSerializer
		);
	}

	/**
	 * @since 0.2
	 * @return ItemMerger
	 */
	public function newItemMerger() {
		return new ItemMerger( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 * @return AliasGroupSetter
	 */
	public function newAliasGroupSetter() {
		return new AliasGroupSetter( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 * @return DescriptionSetter
	 */
	public function newDescriptionSetter() {
		return new DescriptionSetter( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 * @return LabelSetter
	 */
	public function newLabelSetter() {
		return new LabelSetter( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 * @return ReferenceRemover
	 */
	public function newReferenceRemover() {
		return new ReferenceRemover( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 * @return ReferenceSetter
	 */
	public function newReferenceSetter() {
		return new ReferenceSetter(
			$this->newWikibaseApi(),
			$this->newDataModelSerializerFactory()->newReferenceSerializer()
		);
	}

	/**
	 * @since 0.2
	 * @return SiteLinkLinker
	 */
	public function newSiteLinkLinker() {
		return new SiteLinkLinker( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 * @return SiteLinkSetter
	 */
	public function newSiteLinkSetter() {
		return new SiteLinkSetter( $this->newWikibaseApi() );
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
		return new RedirectCreator( $this->newWikibaseApi() );
	}

	private function newDataModelDeserializerFactory() {
		return new DeserializerFactory(
			$this->dataValueDeserializer,
			new BasicEntityIdParser()
		);
	}

	private function newDataModelSerializerFactory() {
		return new SerializerFactory( $this->dataValueSerializer );
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
	 * @since 0.5
	 * @return StatementSetter
	 */
	public function newStatementSetter() {
		return new StatementSetter(
			$this->newWikibaseApi(),
			$this->newDataModelSerializerFactory()->newStatementSerializer()
		);
	}

	/**
	 * @since 0.5
	 * @return StatementCreator
	 */
	public function newStatementCreator() {
		return new StatementCreator(
			$this->newWikibaseApi(),
			$this->dataValueSerializer
		);
	}

	/**
	 * @since 0.5
	 * @return StatementRemover
	 */
	public function newStatementRemover() {
		return new StatementRemover( $this->newWikibaseApi() );
	}

	/**
	 * @return WikibaseApi
	 */
	private function newWikibaseApi() {
		return new WikibaseApi( $this->api );
	}

	/**
	 * @since 0.7
	 * @return EntityLookup
	 */
	public function newEntityLookup() {
		return new EntityApiLookup( $this->newRevisionGetter() );
	}

	/**
	 * @since 0.7
	 * @return ItemLookup
	 */
	public function newItemLookup() {
		return new ItemApiLookup( $this->newEntityLookup() );
	}

	/**
	 * @since 0.7
	 * @return PropertyLookup
	 */
	public function newPropertyLookup() {
		return new PropertyApiLookup( $this->newEntityLookup() );
	}

	/**
	 * @since 0.7
	 * @return TermLookup
	 */
	public function newTermLookup() {
		return new EntityRetrievingTermLookup( $this->newEntityLookup() );
	}

	/**
	 * @since 0.7
	 * @return EntityDocumentSaver
	 */
	public function newEntityDocumentSaver() {
		return new EntityDocumentSaver( $this->newRevisionSaver() );
	}

	/**
	 * @since 0.8
	 * @return EntitySearcher
	 */
	public function newEntitySearcher() {
		return new EntitySearcher( $this->api );
	}

}
