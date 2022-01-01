<?php

namespace Addwiki\Wikibase\Api;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
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
 * @access public
 */
class WikibaseFactory {

	private ActionApi $api;

	private Deserializer $dataValueDeserializer;

	private Serializer $dataValueSerializer;

	public function __construct( ActionApi $api, Deserializer $dvDeserializer, Serializer $dvSerializer ) {
		$this->api = $api;
		$this->dataValueDeserializer = $dvDeserializer;
		$this->dataValueSerializer = $dvSerializer;
	}

	public function newRevisionSaver(): RevisionSaver {
		return new RevisionSaver(
			$this->newWikibaseApi(),
			$this->newDataModelDeserializerFactory()->newEntityDeserializer(),
			$this->newDataModelSerializerFactory()->newEntitySerializer()
		);
	}

	public function newRevisionGetter(): RevisionGetter {
		return new RevisionGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newEntityDeserializer()
		);
	}

	public function newRevisionsGetter(): RevisionsGetter {
		return new RevisionsGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newEntityDeserializer()
		);
	}

	public function newValueParser(): ValueParser {
		return new ValueParser(
			$this->api,
			$this->dataValueDeserializer
		);
	}

	public function newValueFormatter(): ValueFormatter {
		return new ValueFormatter(
			$this->api,
			$this->dataValueSerializer
		);
	}

	public function newItemMerger(): ItemMerger {
		return new ItemMerger( $this->newWikibaseApi() );
	}

	public function newAliasGroupSetter(): AliasGroupSetter {
		return new AliasGroupSetter( $this->newWikibaseApi() );
	}

	public function newDescriptionSetter(): DescriptionSetter {
		return new DescriptionSetter( $this->newWikibaseApi() );
	}

	public function newLabelSetter(): LabelSetter {
		return new LabelSetter( $this->newWikibaseApi() );
	}

	public function newReferenceRemover(): ReferenceRemover {
		return new ReferenceRemover( $this->newWikibaseApi() );
	}

	public function newReferenceSetter(): ReferenceSetter {
		return new ReferenceSetter(
			$this->newWikibaseApi(),
			$this->newDataModelSerializerFactory()->newReferenceSerializer()
		);
	}

	public function newSiteLinkLinker(): SiteLinkLinker {
		return new SiteLinkLinker( $this->newWikibaseApi() );
	}

	public function newSiteLinkSetter(): SiteLinkSetter {
		return new SiteLinkSetter( $this->newWikibaseApi() );
	}

	public function newBadgeIdsGetter(): BadgeIdsGetter {
		return new BadgeIdsGetter( $this->api );
	}

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

	public function newStatementGetter(): StatementGetter {
		return new StatementGetter(
			$this->api,
			$this->newDataModelDeserializerFactory()->newStatementDeserializer()
		);
	}

	public function newStatementSetter(): StatementSetter {
		return new StatementSetter(
			$this->newWikibaseApi(),
			$this->newDataModelSerializerFactory()->newStatementSerializer()
		);
	}

	public function newStatementCreator(): StatementCreator {
		return new StatementCreator(
			$this->newWikibaseApi(),
			$this->dataValueSerializer
		);
	}

	public function newStatementRemover(): StatementRemover {
		return new StatementRemover( $this->newWikibaseApi() );
	}

	private function newWikibaseApi(): WikibaseApi {
		return new WikibaseApi( $this->api );
	}

	public function newEntityLookup(): EntityApiLookup {
		return new EntityApiLookup( $this->newRevisionGetter() );
	}

	public function newItemLookup(): ItemApiLookup {
		return new ItemApiLookup( $this->newEntityLookup() );
	}

	public function newPropertyLookup(): PropertyApiLookup {
		return new PropertyApiLookup( $this->newEntityLookup() );
	}

	public function newTermLookup(): EntityRetrievingTermLookup {
		return new EntityRetrievingTermLookup( $this->newEntityLookup() );
	}

	public function newEntityDocumentSaver(): EntityDocumentSaver {
		return new EntityDocumentSaver( $this->newRevisionSaver() );
	}

	public function newEntitySearcher(): EntitySearcher {
		return new EntitySearcher( $this->api );
	}

}
