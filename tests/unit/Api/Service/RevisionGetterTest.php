<?php

namespace Wikibase\Api\Test;

use Deserializers\Deserializer;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;
use Mediawiki\DataModel\Revision;
use PHPUnit\Framework\TestCase;
use Wikibase\Api\Service\RevisionGetter;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\ItemContent;

/**
 * @author Addshore
 *
 * @covers Wikibase\Api\Service\RevisionGetter
 */
class RevisionGetterTest extends TestCase {

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|MediawikiApi
	 */
	private function createMockApi() {
		return $this->createMock( MediawikiApi::class );
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|Deserializer
	 */
	public function createMockDeserializer() {
		return $this->createMock( Deserializer::class );
	}

	public function testValidConstructionWorks() {
		new RevisionGetter( $this->createMockApi(), $this->createMockDeserializer() );
		$this->assertTrue( true );
	}

	public function provideIds() {
		return [
			[ 'Q1' ],
			[ ItemId::newFromNumber( 1 ) ],
		];
	}

	/**
	 * @dataProvider provideIds
	 */
	public function testGetFromId( $id ) {
		$api = $this->createMockApi();
		$api->expects( $this->once() )
			->method( 'getRequest' )
			->with(
				$this->equalTo( new SimpleRequest(
					'wbgetentities',
					[ 'ids' => 'Q1' ]
				) )
			)
			->will( $this->returnValue( [ 'entities' => [ 'Q123' => [
				'pageid' => '111',
				'lastrevid' => '222',
				'modified' => 'TIMESTAMP'
			] ] ] ) );
		$deserializer = $this->createMockDeserializer();
		$deserializer->expects( $this->once() )
			->method( 'deserialize' )
			->with( $this->equalTo( [
						'pageid' => '111',
						'lastrevid' => '222',
						'modified' => 'TIMESTAMP'
			] ) )
			->will( $this->returnValue( new Item() ) );

		$service = new RevisionGetter( $api, $deserializer );
		$result = $service->getFromId( $id );

		$this->assertInstanceOf( Revision::class, $result );
		$this->assertInstanceOf( ItemContent::class, $result->getContent() );
		$this->assertInstanceOf( Item::class, $result->getContent()->getData() );
		$this->assertEquals( 111, $result->getPageIdentifier()->getId() );
		$this->assertEquals( 'TIMESTAMP', $result->getTimestamp() );
	}

}
