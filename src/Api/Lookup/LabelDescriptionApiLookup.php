<?php

namespace Wikibase\Api\Lookup;

use OutOfBoundsException;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\LabelDescriptionLookup;
use Wikibase\DataModel\Term\DescriptionsProvider;
use Wikibase\DataModel\Term\LabelsProvider;
use Wikibase\DataModel\Term\Term;

class LabelDescriptionApiLookup implements LabelDescriptionLookup {

	/**
	 * @var string
	 */
	private $languageCode;

	/**
	 * @var EntityLookup
	 */
	private $entityLookup;

	/**
	 * @param string $languageCode
	 * @param EntityLookup $entityLookup
	 */
	public function __construct( $languageCode, EntityLookup $entityLookup ) {
		$this->languageCode = $languageCode;
		$this->entityLookup = $entityLookup;
	}

	/**
	 * @param EntityId $entityId
	 *
	 * @throws OutOfBoundsException if no such label or entity could be found
	 * @return Term
	 */
	public function getLabel( EntityId $entityId ) {
		$entity = $this->entityLookup->getEntity( $entityId );
		if( is_null( $entity ) ) {
			throw new OutOfBoundsException( 'Could not lookup entity' );
		}
		if( $entity instanceof LabelsProvider ) {
			return $entity->getLabels()->getByLanguage( $this->languageCode )->getText();
		} else {
			throw new OutOfBoundsException( 'Got entity but was not a LabelsProvider' );
		}
	}

	/**
	 * @param EntityId $entityId
	 *
	 * @throws OutOfBoundsException if no such description or entity could be found
	 * @return Term
	 */
	public function getDescription( EntityId $entityId ) {
		$entity = $this->entityLookup->getEntity( $entityId );
		if( is_null( $entity ) ) {
			throw new OutOfBoundsException( 'Could not lookup entity' );
		}
		if( $entity instanceof DescriptionsProvider ) {
			return $entity->getDescriptions()->getByLanguage( $this->languageCode )->getText();
		} else {
			throw new OutOfBoundsException( 'Got entity but was not a DescriptionsProvider' );
		}
	}
}
