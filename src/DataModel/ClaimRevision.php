<?php

namespace Wikibase\Api\DataModel;

use Wikibase\DataModel\Claim\Claim;
use Wikibase\DataModel\Claim\Statement;

class ClaimRevision {

	protected $dataEntity;
	protected $lastRevId;

	/**
	 * @param Claim $dataItem
	 * @param int $lastRevId
	 */
	public function __construct( Claim $dataItem, $lastRevId ) {
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
	 * @return Claim|Statement
	 */
	public function getData() {
		return $this->dataEntity;
	}

}