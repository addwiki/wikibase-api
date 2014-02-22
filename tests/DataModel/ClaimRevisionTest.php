<?php

namespace Wikibase\Api\Test\DataModel;

use Wikibase\Api\DataModel\ClaimRevision;

/**
 * @covers Wikibase\Api\DataModel\ClaimRevision
 */
class ClaimRevisionTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testValidConstruction( $dataItem, $lastRevId ) {
		$entityRevision = new ClaimRevision( $dataItem, $lastRevId );
		$this->assertEquals( $dataItem, $entityRevision->getData() );
		$this->assertEquals( $lastRevId, $entityRevision->getLastRevId() );
	}

	public function provideValidConstruction() {
		$mockClaim = $this->getMockBuilder( 'Wikibase\DataModel\Claim\Claim' )
			->disableOriginalConstructor()
			->getMock();
		$mockStatement = $this->getMockBuilder( 'Wikibase\DataModel\Claim\Statement' )
			->disableOriginalConstructor()
			->getMock();
		return array(
			array( $mockClaim, 1234 ),
			array( $mockStatement, 1234 ),
		);
	}

} 