<?php

namespace Addwiki\Wikibase\Api\Tests\Unit\Lookup;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Wikibase\Api\Lookup\EntityRedirectApiLookup;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers Wikibase\Api\Lookup\EntityRedirectApiLookup
 */
class EntityRedirectApiLookupTest extends TestCase {

	public function testGetRedirectForEntityId(): void {
		/** @var ActionApi|MockObject $apiMock */
		$apiMock = $this->createMock( ActionApi::class );
		$apiMock->expects( $this->once() )
			->method( 'request' )
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
