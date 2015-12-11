<?php

namespace Wikibase\Api\Lookup\Test;

use Wikibase\Api\Lookup\ItemApiLookup;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers Wikibase\Api\Lookup\ItemApiLookup
 */
class ItemApiLookupTest extends \PHPUnit_Framework_TestCase {

	public function testGetItemForId() {
		$lookupMock = $this->getMockBuilder( '\Wikibase\DataModel\Services\Lookup\EntityLookup' )
			->disableOriginalConstructor()
			->getMock();
		$lookupMock->expects( $this->once() )
			->method( 'getEntity' )
			->with( $this->equalTo( new ItemId( 'Q42' ) ) )
			->will( $this->returnValue( new Item( new ItemId( 'Q42' ) ) ) );

		$itemApiLookup = new ItemApiLookup( $lookupMock );
		$this->assertEquals(
			new Item( new ItemId( 'Q42' ) ),
			$itemApiLookup->getItemForId( new ItemId( 'Q42' ) )
		);
	}

	public function testGetItemForIdWithException() {
		$lookupMock = $this->getMockBuilder( '\Wikibase\DataModel\Services\Lookup\EntityLookup' )
			->disableOriginalConstructor()
			->getMock();
		$lookupMock->expects( $this->once() )
			->method( 'getEntity' )
			->with( $this->equalTo( new ItemId( 'Q42' ) ) )
			->will( $this->returnValue( null ) );

		$itemApiLookup = new ItemApiLookup( $lookupMock );

		$result = $itemApiLookup->getItemForId( new ItemId( 'Q42' ) );
		$this->assertEquals( null, $result );
	}
}
