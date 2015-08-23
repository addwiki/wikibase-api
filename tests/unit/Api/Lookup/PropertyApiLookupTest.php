<?php

namespace Wikibase\Api\Lookup\Test;

use Wikibase\Api\Lookup\PropertyApiLookup;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Wikibase\Api\Lookup\PropertyApiLookup
 */
class PropertyApiLookupTest extends \PHPUnit_Framework_TestCase {

	public function testGetPropertyForId() {
		$property = new Property( new PropertyId( 'P42' ), null, 'string' );

		$lookupMock = $this->getMockBuilder( '\Wikibase\DataModel\Services\Lookup\EntityLookup' )
			->disableOriginalConstructor()
			->getMock();
		$lookupMock->expects( $this->once() )
			->method( 'getEntity' )
			->with( $this->equalTo( new PropertyId( 'P42' ) ) )
			->will( $this->returnValue( $property ) );

		$propertyApiLookup = new PropertyApiLookup( $lookupMock );
		$this->assertEquals(
			$property,
			$propertyApiLookup->getPropertyForId( new PropertyId( 'P42' ) )
		);
	}

	public function testGetPropertyForIdWithException() {
		$lookupMock = $this->getMockBuilder( '\Wikibase\DataModel\Services\Lookup\EntityLookup' )
			->disableOriginalConstructor()
			->getMock();
		$lookupMock->expects( $this->once() )
			->method( 'getEntity' )
			->with( $this->equalTo( new PropertyId( 'P42' ) ) )
			->will( $this->returnValue( null ) );

		$propertyApiLookup = new PropertyApiLookup( $lookupMock );

		$this->setExpectedException( 'Wikibase\DataModel\Services\Lookup\PropertyNotFoundException' );
		$propertyApiLookup->getPropertyForId( new PropertyId( 'P42' ) );
	}
}
