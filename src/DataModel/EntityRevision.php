<?php

namespace Wikibase\Api\DataModel;

use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;

class EntityRevision {

	protected $dataEntity;
	protected $lastRevId;

	/**
	 * @param Entity $dataItem
	 * @param int $lastRevId
	 */
	public function __construct( Entity $dataItem, $lastRevId ) {
		$this->dataEntity = $dataItem;
		$this->lastRevId = $lastRevId;
	}

	/**
	 * @return int
	 */
	public function getLastRevId() {
		return $this->lastRevId;
	}

	/**
	 * @return Entity|Item|Property
	 */
	public function getData() {
		return $this->dataEntity;
	}

}