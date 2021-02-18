<?php

namespace Addwiki\Wikibase\Api\Service;

use Addwiki\Mediawiki\DataModel\Content;
use Addwiki\Mediawiki\DataModel\EditInfo;
use Addwiki\Mediawiki\DataModel\Revision;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;

/**
 * @access private
 *
 * @author Addshore
 */
class EntityDocumentSaver {

	private RevisionSaver $revisionSaver;

	public function __construct( RevisionSaver $revisionSaver ) {
		$this->revisionSaver = $revisionSaver;
	}

	/**
	 * @since 0.7
	 *
	 * @param EntityDocument $entityDocument
	 * @param EditInfo $editInfo
	 *
	 * @return Item|Property
	 */
	public function save( EntityDocument $entityDocument, EditInfo $editInfo ) {
		return $this->revisionSaver->save( new Revision( new Content( $entityDocument ) ), $editInfo );
	}

}
