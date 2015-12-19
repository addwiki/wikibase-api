<?php

namespace Wikibase\Api\Service;

use Mediawiki\DataModel\Content;
use Mediawiki\DataModel\EditInfo;
use Mediawiki\DataModel\Revision;
use Wikibase\DataModel\Entity\EntityDocument;

/**
 * @access private
 *
 * @author Addshore
 */
class EntityDocumentSaver {

	/**
	 * @var RevisionSaver
	 */
	private $revisionSaver;

	public function __construct( RevisionSaver $revisionSaver ) {
		$this->revisionSaver = $revisionSaver;
	}

	/**
	 * @since 0.7
	 *
	 * @param EntityDocument $entityDocument
	 * @param EditInfo $editInfo
	 *
	 * @return EntityDocument
	 */
	public function save( EntityDocument $entityDocument, EditInfo $editInfo ) {
		return $this->revisionSaver->save( new Revision( new Content( $entityDocument ) ), $editInfo );
	}

}
