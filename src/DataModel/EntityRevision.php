<?php

namespace Wikibase\Api\DataModel;

use Wikibase\DataModel\Entity\Entity;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;

class EntityRevision {

	/**
	 * @var Entity
	 */
	protected $dataEntity;

	/**
	 * @var int|null
	 */
	protected $lastRevId;

	/**
	 * @param Entity $dataItem
	 * @param int|null $lastRevId
	 */
	public function __construct( Entity $dataItem, $lastRevId = null ) {
		$this->dataEntity = $dataItem;
		$this->lastRevId = $lastRevId;
	}

	/**
	 * @return int|null
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