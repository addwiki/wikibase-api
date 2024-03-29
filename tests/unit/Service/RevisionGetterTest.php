<?php

namespace Addwiki\Wikibase\Api\Tests\Unit\Service;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Mediawiki\Api\Client\Action\Request\ActionRequest;
use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Wikibase\Api\Service\RevisionGetter;
use Addwiki\Wikibase\DataModel\ItemContent;
use Deserializers\Deserializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;

class RevisionGetterTest extends TestCase {

	/**
	 * @return MockObject|ActionApi
	 */
	private function createMockApi() {
		return $this->createMock( ActionApi::class );
	}

	/**
	 * @return MockObject|Deserializer
	 */
	public function createMockDeserializer() {
		return $this->createMock( Deserializer::class );
	}

	public function testValidConstructionWorks(): void {
		new RevisionGetter( $this->createMockApi(), $this->createMockDeserializer() );
		$this->assertTrue( true );
	}

	public function provideIds(): array {
		return [
			[ 'Q1' ],
			[ ItemId::newFromNumber( 1 ) ],
		];
	}

	/**
	 * @dataProvider provideIds
	 */
	public function testGetFromId( $id ): void {
		$api = $this->createMockApi();
		$api->expects( $this->once() )
			->method( 'request' )
			->with(
				ActionRequest::simpleGet(
					'wbgetentities',
					[ 'ids' => 'Q1' ]
				)
			)
			->willReturn( [ 'entities' => [ 'Q123' => [
				'pageid' => '111',
				'lastrevid' => '222',
				'modified' => 'TIMESTAMP'
			] ] ] );
		$deserializer = $this->createMockDeserializer();
		$deserializer->expects( $this->once() )
			->method( 'deserialize' )
			->with( [
						'pageid' => '111',
						'lastrevid' => '222',
						'modified' => 'TIMESTAMP'
			] )
			->willReturn( new Item() );

		$service = new RevisionGetter( $api, $deserializer );
		$result = $service->getFromId( $id );

		$this->assertInstanceOf( Revision::class, $result );
		$this->assertInstanceOf( ItemContent::class, $result->getContent() );
		$this->assertInstanceOf( Item::class, $result->getContent()->getData() );
		$this->assertEquals( 111, $result->getPageIdentifier()->getId() );
		$this->assertEquals( 'TIMESTAMP', $result->getTimestamp() );
	}

}
