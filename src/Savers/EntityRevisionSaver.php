<?php

namespace Wikibase\Api\Savers;

use Mediawiki\Api\MediawikiApi;
use Wikibase\Api\DataModel\EntityRevision;
use Wikibase\DataModel\SerializerFactory;

class EntityRevisionSaver {

	/**
	 * @var MediawikiApi
	 */
	protected $api;

	/**
	 * @var SerializerFactory
	 */
	protected $serializerFactory;

	/**
	 * @param MediawikiApi $api
	 */
	public function __construct( MediawikiApi $api ) {
		$this->api = $api;
		//TODO set $this->serializerFactory
	}

	/**
	 * @param EntityRevision $entityRevision
	 * @returns bool
	 */
	public function save( EntityRevision $entityRevision ) {
		//TODO
	}

} 