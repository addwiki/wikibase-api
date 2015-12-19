<?php

namespace Wikibase\Api\Lookup\Test;

use Mediawiki\Api\MediawikiApi;
use PHPUnit_Framework_MockObject_MockObject;
use Wikibase\Api\Lookup\EntityRedirectApiLookup;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers Wikibase\Api\Lookup\EntityRedirectApiLookup
 *
 * @author Addshore
 */
class EntityRedirectApiLookupTest extends \PHPUnit_Framework_TestCase {

	public function testGetRedirectForEntityId() {
		/** @var MediawikiApi|PHPUnit_Framework_MockObject_MockObject $apiMock */
		$apiMock = $this->getMockBuilder( 'Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		$apiMock->expects( $this->once() )
			->method( 'getRequest' )
			->will( $this->returnValue( array(
				'entities' => array(
					'Q404' => array(
						'redirects' => array(
							'to' => 'Q395',
						),
					),
				),
			)) );

		$lookup = new EntityRedirectApiLookup( $apiMock );
		$this->assertEquals(
			new ItemId( 'Q395' ),
			$lookup->getRedirectForEntityId( new ItemId( 'Q404' ) )
		);
	}

}
