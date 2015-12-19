<?php

namespace Wikibase\Api\Lookup;

use OutOfBoundsException;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;
use Wikibase\DataModel\Services\Lookup\TermLookup;
use Wikibase\DataModel\Term\DescriptionsProvider;
use Wikibase\DataModel\Term\LabelsProvider;

/**
 * @access private
 * 
 * @author Addshore
 */
class TermApiLookup implements TermLookup {

	/**
	 * @var EntityLookup
	 */
	private $entityLookup;

	/**
	 * @param EntityLookup $entityLookup
	 */
	public function __construct( EntityLookup $entityLookup ) {
		$this->entityLookup = $entityLookup;
	}

	public function getLabel( EntityId $entityId, $languageCode ) {
		$entity = $this->entityLookup->getEntity( $entityId );
		if( is_null( $entity ) ) {
			throw new OutOfBoundsException( 'Could not lookup entity' );
		}
		if( $entity instanceof LabelsProvider ) {
			return $entity->getLabels()->getByLanguage( $languageCode )->getText();
		} else {
			throw new OutOfBoundsException( 'Got entity but was not a LabelsProvider' );
		}
	}

	public function getLabels( EntityId $entityId, array $languageCodes ) {
		$entity = $this->entityLookup->getEntity( $entityId );
		if( is_null( $entity ) ) {
			throw new OutOfBoundsException( 'Could not lookup entity' );
		}
		if( $entity instanceof LabelsProvider ) {
			return $entity->getLabels()->getWithLanguages( $languageCodes )->toTextArray();
		} else {
			throw new OutOfBoundsException( 'Got entity but was not a LabelsProvider' );
		}
	}

	public function getDescription( EntityId $entityId, $languageCode ) {
		$entity = $this->entityLookup->getEntity( $entityId );
		if( is_null( $entity ) ) {
			throw new OutOfBoundsException( 'Could not lookup entity' );
		}
		if( $entity instanceof DescriptionsProvider ) {
			return $entity->getDescriptions()->getByLanguage( $languageCode )->getText();
		} else {
			throw new OutOfBoundsException( 'Got entity but was not a DescriptionsProvider' );
		}
	}

	public function getDescriptions( EntityId $entityId, array $languageCodes ) {
		$entity = $this->entityLookup->getEntity( $entityId );
		if( is_null( $entity ) ) {
			throw new OutOfBoundsException( 'Could not lookup entity' );
		}
		if( $entity instanceof DescriptionsProvider ) {
			return $entity->getDescriptions()->getWithLanguages( $languageCodes )->toTextArray();
		} else {
			throw new OutOfBoundsException( 'Got entity but was not a DescriptionsProvider' );
		}
	}

}
