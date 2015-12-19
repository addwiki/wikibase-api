<?php

namespace Wikibase\Api\Lookup\Test;

use PHPUnit_Framework_MockObject_MockObject;
use Wikibase\Api\Lookup\PropertyApiLookup;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;

/**
 * @author Addshore
 *
 * @covers Wikibase\Api\Lookup\PropertyApiLookup
 */
class PropertyApiLookupTest extends \PHPUnit_Framework_TestCase {

	public function testGetPropertyForId() {
		$property = new Property( new PropertyId( 'P42' ), null, 'string' );

		/** @var EntityLookup|PHPUnit_Framework_MockObject_MockObject $lookupMock */
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
		/** @var EntityLookup|PHPUnit_Framework_MockObject_MockObject $lookupMock */
		$lookupMock = $this->getMockBuilder( '\Wikibase\DataModel\Services\Lookup\EntityLookup' )
			->disableOriginalConstructor()
			->getMock();
		$lookupMock->expects( $this->once() )
			->method( 'getEntity' )
			->with( $this->equalTo( new PropertyId( 'P42' ) ) )
			->will( $this->returnValue( null ) );

		$propertyApiLookup = new PropertyApiLookup( $lookupMock );

		$result = $propertyApiLookup->getPropertyForId( new PropertyId( 'P42' ) );
		$this->assertEquals( null, $result );
	}
}
