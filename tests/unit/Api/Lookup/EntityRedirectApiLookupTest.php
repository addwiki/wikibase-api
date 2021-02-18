<?php

namespace Addwiki\Wikibase\Tests\Unit\Api\Lookup;

use Addwiki\Mediawiki\Api\Client\MediawikiApi;
use Addwiki\Wikibase\Api\Lookup\EntityRedirectApiLookup;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers Wikibase\Api\Lookup\EntityRedirectApiLookup
 *
 * @author Addshore
 */
class EntityRedirectApiLookupTest extends TestCase {

	public function testGetRedirectForEntityId(): void {
		/** @var MediawikiApi|MockObject $apiMock */
		$apiMock = $this->createMock( MediawikiApi::class );
		$apiMock->expects( $this->once() )
			->method( 'getRequest' )
			->willReturn( [
				'entities' => [
					'Q404' => [
						'redirects' => [
							'to' => 'Q395',
						],
					],
				],
			] );

		$lookup = new EntityRedirectApiLookup( $apiMock );
		$this->assertEquals(
			new ItemId( 'Q395' ),
			$lookup->getRedirectForEntityId( new ItemId( 'Q404' ) )
		);
	}

}
