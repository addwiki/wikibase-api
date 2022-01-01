<?php

namespace Addwiki\Wikibase\Api\Tests\Unit\Lookup;

use Addwiki\Wikibase\Api\Lookup\ItemApiLookup;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Services\Lookup\EntityLookup;

/**
 * @covers Wikibase\Api\Lookup\ItemApiLookup
 */
class ItemApiLookupTest extends TestCase {

	public function testGetItemForId(): void {
		/** @var EntityLookup|MockObject $lookupMock */
		$lookupMock = $this->createMock( EntityLookup::class );
		$lookupMock->expects( $this->once() )
			->method( 'getEntity' )
			->with( new ItemId( 'Q42' ) )
			->willReturn( new Item( new ItemId( 'Q42' ) ) );

		$itemApiLookup = new ItemApiLookup( $lookupMock );
		$this->assertEquals(
			new Item( new ItemId( 'Q42' ) ),
			$itemApiLookup->getItemForId( new ItemId( 'Q42' ) )
		);
	}

	public function testGetItemForIdWithException(): void {
		/** @var EntityLookup|PHPUnit_Framework_MockObject_MockObject $lookupMock */
		$lookupMock = $this->createMock( EntityLookup::class );
		$lookupMock->expects( $this->once() )
			->method( 'getEntity' )
			->with( $this->equalTo( new ItemId( 'Q42' ) ) )
			->will( $this->returnValue( null ) );

		$itemApiLookup = new ItemApiLookup( $lookupMock );

		$result = $itemApiLookup->getItemForId( new ItemId( 'Q42' ) );
		$this->assertNull( $result );
	}
}
