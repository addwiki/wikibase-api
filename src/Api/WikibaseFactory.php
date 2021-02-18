<?php

namespace Addwiki\Wikibase\Api;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Wikibase\Api\Lookup\EntityApiLookup;
use Addwiki\Wikibase\Api\Lookup\ItemApiLookup;
use Addwiki\Wikibase\Api\Lookup\PropertyApiLookup;
use Addwiki\Wikibase\Api\Service\AliasGroupSetter;
use Addwiki\Wikibase\Api\Service\BadgeIdsGetter;
use Addwiki\Wikibase\Api\Service\DescriptionSetter;
use Addwiki\Wikibase\Api\Service\EntityDocumentSaver;
use Addwiki\Wikibase\Api\Service\EntitySearcher;
use Addwiki\Wikibase\Api\Service\ItemMerger;
use Addwiki\Wikibase\Api\Service\LabelSetter;
use Addwiki\Wikibase\Api\Service\RedirectCreator;
use Addwiki\Wikibase\Api\Service\ReferenceRemover;
use Addwiki\Wikibase\Api\Service\ReferenceSetter;
use Addwiki\Wikibase\Api\Service\RevisionGetter;
use Addwiki\Wikibase\Api\Service\RevisionSaver;
use Addwiki\Wikibase\Api\Service\RevisionsGetter;
use Addwiki\Wikibase\Api\Service\SiteLinkLinker;
use Addwiki\Wikibase\Api\Service\SiteLinkSetter;
use Addwiki\Wikibase\Api\Service\StatementCreator;
use Addwiki\Wikibase\Api\Service\StatementGetter;
use Addwiki\Wikibase\Api\Service\StatementRemover;
use Addwiki\Wikibase\Api\Service\StatementSetter;
use Addwiki\Wikibase\Api\Service\ValueFormatter;
use Addwiki\Wikibase\Api\Service\ValueParser;
use Deserializers\Deserializer;
use Serializers\Serializer;
use Wikibase\DataModel\DeserializerFactory;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\SerializerFactory;
use Wikibase\DataModel\Services\Lookup\EntityRetrievingTermLookup;

/**
 * @author Addshore
 *
 * @access public
 */
class WikibaseFactory {

	private MediawikiApi $api;

	private \Deserializers\Deserializer $dataValueDeserializer;

	private \Serializers\Serializer $dataValueSerializer;

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
	 */
	public function newRevisionSaver(): RevisionSaver {
		return new RevisionSaver(
			$this->newWikibaseApi(),
			$this->newDataModelDeserializerFactory()->newEntityDeserializer(),
			$this->newDataModelSerializerFactory()->newEntitySerializer()
		);
	}

	/**
	 * @since 0.1
	 */
	public function newRevisionGetter(): RevisionGetter {
		return new RevisionGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newEntityDeserializer()
		);
	}

	/**
	 * @since 0.4
	 */
	public function newRevisionsGetter(): RevisionsGetter {
		return new RevisionsGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newEntityDeserializer()
		);
	}

	/**
	 * @since 0.2
	 */
	public function newValueParser(): ValueParser {
		return new ValueParser(
			$this->api,
			$this->dataValueDeserializer
		);
	}

	/**
	 * @since 0.2
	 */
	public function newValueFormatter(): ValueFormatter {
		return new ValueFormatter(
			$this->api,
			$this->dataValueSerializer
		);
	}

	/**
	 * @since 0.2
	 */
	public function newItemMerger(): ItemMerger {
		return new ItemMerger( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 */
	public function newAliasGroupSetter(): AliasGroupSetter {
		return new AliasGroupSetter( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 */
	public function newDescriptionSetter(): DescriptionSetter {
		return new DescriptionSetter( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 */
	public function newLabelSetter(): LabelSetter {
		return new LabelSetter( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 */
	public function newReferenceRemover(): ReferenceRemover {
		return new ReferenceRemover( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 */
	public function newReferenceSetter(): ReferenceSetter {
		return new ReferenceSetter(
			$this->newWikibaseApi(),
			$this->newDataModelSerializerFactory()->newReferenceSerializer()
		);
	}

	/**
	 * @since 0.2
	 */
	public function newSiteLinkLinker(): SiteLinkLinker {
		return new SiteLinkLinker( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.2
	 */
	public function newSiteLinkSetter(): SiteLinkSetter {
		return new SiteLinkSetter( $this->newWikibaseApi() );
	}

	/**
	 * @since 0.5
	 */
	public function newBadgeIdsGetter(): BadgeIdsGetter {
		return new BadgeIdsGetter( $this->api );
	}

	/**
	 * @since 0.5
	 */
	public function newRedirectCreator(): RedirectCreator {
		return new RedirectCreator( $this->newWikibaseApi() );
	}

	private function newDataModelDeserializerFactory(): DeserializerFactory {
		return new DeserializerFactory(
			$this->dataValueDeserializer,
			new BasicEntityIdParser()
		);
	}

	private function newDataModelSerializerFactory(): SerializerFactory {
		return new SerializerFactory( $this->dataValueSerializer );
	}

	/**
	 * @since 0.5
	 */
	public function newStatementGetter(): StatementGetter {
		return new StatementGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newStatementDeserializer()
		);
	}

	/**
	 * @since 0.5
	 */
	public function newStatementSetter(): StatementSetter {
		return new StatementSetter(
			$this->newWikibaseApi(),
			$this->newDataModelSerializerFactory()->newStatementSerializer()
		);
	}

	/**
	 * @since 0.5
	 */
	public function newStatementCreator(): StatementCreator {
		return new StatementCreator(
			$this->newWikibaseApi(),
			$this->dataValueSerializer
		);
	}

	/**
	 * @since 0.5
	 */
	public function newStatementRemover(): StatementRemover {
		return new StatementRemover( $this->newWikibaseApi() );
	}

	private function newWikibaseApi(): WikibaseApi {
		return new WikibaseApi( $this->api );
	}

	/**
	 * @since 0.7
	 */
	public function newEntityLookup(): EntityApiLookup {
		return new EntityApiLookup( $this->newRevisionGetter() );
	}

	/**
	 * @since 0.7
	 */
	public function newItemLookup(): ItemApiLookup {
		return new ItemApiLookup( $this->newEntityLookup() );
	}

	/**
	 * @since 0.7
	 */
	public function newPropertyLookup(): PropertyApiLookup {
		return new PropertyApiLookup( $this->newEntityLookup() );
	}

	/**
	 * @since 0.7
	 */
	public function newTermLookup(): EntityRetrievingTermLookup {
		return new EntityRetrievingTermLookup( $this->newEntityLookup() );
	}

	/**
	 * @since 0.7
	 */
	public function newEntityDocumentSaver(): EntityDocumentSaver {
		return new EntityDocumentSaver( $this->newRevisionSaver() );
	}

	/**
	 * @since 0.8
	 */
	public function newEntitySearcher(): EntitySearcher {
		return new EntitySearcher( $this->api );
	}

}
