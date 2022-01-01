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
use Addwiki\Wikibase\DataModel\DataModelFactory;
use Wikibase\DataModel\Services\Lookup\EntityRetrievingTermLookup;

/**
 * @access public
 */
class WikibaseFactory {

	private ActionApi $api;

	private DataModelFactory $datamodelFactory;

	public function __construct( ActionApi $api, $datamodelFactory ) {
		$this->api = $api;

		if ( $datamodelFactory instanceof DataModelFactory ) {
			$this->datamodelFactory = $datamodelFactory;
		} else {
			// Back compact from older constructor signature
			// ( ActionApi $api, Deserializer $dvDeserializer, Serializer $dvSerializer )
			$arg_list = func_get_args();
			$this->datamodelFactory = new DataModelFactory(
				$arg_list[1],
				$arg_list[2]
			);
		}
	}

	public function newRevisionSaver(): RevisionSaver {
		return new RevisionSaver(
			$this->newWikibaseApi(),
			$this->datamodelFactory->newEntityDeserializer(),
			$this->datamodelFactory->newEntitySerializer()
		);
	}

	public function newRevisionGetter(): RevisionGetter {
		return new RevisionGetter(
			$this->api,
			$this->datamodelFactory->newEntityDeserializer()
		);
	}

	public function newRevisionsGetter(): RevisionsGetter {
		return new RevisionsGetter(
			$this->api,
			$this->datamodelFactory->newEntityDeserializer()
		);
	}

	public function newValueParser(): ValueParser {
		return new ValueParser(
			$this->api,
			$this->datamodelFactory->getDataValueDeserializer()
		);
	}

	public function newValueFormatter(): ValueFormatter {
		return new ValueFormatter(
			$this->api,
			$this->datamodelFactory->getDataValueSerializer()
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
			$this->datamodelFactory->newReferenceSerializer()
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

	public function newStatementGetter(): StatementGetter {
		return new StatementGetter(
			$this->api,
			$this->datamodelFactory->newStatementDeserializer()
		);
	}

	public function newStatementSetter(): StatementSetter {
		return new StatementSetter(
			$this->newWikibaseApi(),
			$this->datamodelFactory->newStatementSerializer()
		);
	}

	public function newStatementCreator(): StatementCreator {
		return new StatementCreator(
			$this->newWikibaseApi(),
			$this->datamodelFactory->getDataValueSerializer()
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
