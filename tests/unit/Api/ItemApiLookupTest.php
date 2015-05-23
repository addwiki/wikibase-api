<?php

namespace Wikibase\Api\Test;

use Mediawiki\DataModel\Revision;
use Wikibase\Api\ItemApiLookup;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\ItemContent;

/**
 * @covers Wikibase\Api\ItemApiLookup
 */
class ItemApiLookupTest extends \PHPUnit_Framework_TestCase {

	public function testGetItemForId() {
		$revisionGetterMock = $this->getMockBuilder( '\Wikibase\Api\Service\RevisionGetter' )
			->disableOriginalConstructor()
			->getMock();
		$revisionGetterMock->expects( $this->once() )
			->method( 'getFromId' )
			->with( $this->equalTo( new ItemId( 'Q42' ) ) )
			->will( $this->returnValue( new Revision( new ItemContent( new Item( new ItemId( 'Q42' ) ) ) ) ) );

		$itemApiLookup = new ItemApiLookup( $revisionGetterMock );
		$this->assertEquals(
			new Item( new ItemId( 'Q42' ) ),
			$itemApiLookup->getItemForId( new ItemId( 'Q42' ) )
		);
	}

	public function testGetItemForIdWithException() {
		$revisionGetterMock = $this->getMockBuilder( '\Wikibase\Api\Service\RevisionGetter' )
			->disableOriginalConstructor()
			->getMock();
		$revisionGetterMock->expects( $this->once() )
			->method( 'getFromId' )
			->with( $this->equalTo( new ItemId( 'Q42' ) ) )
			->will( $this->returnValue( false ) );

		$itemApiLookup = new ItemApiLookup( $revisionGetterMock );

		$this->setExpectedException( 'Wikibase\DataModel\Entity\ItemNotFoundException' );
		$itemApiLookup->getItemForId( new ItemId( 'Q42' ) );
	}
}
