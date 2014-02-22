<?php

namespace Wikibase\Api\Test\DataModel;

use Wikibase\Api\DataModel\EntityRevision;

/**
 * @covers Wikibase\Api\DataModel\EntityRevision
 */
class EntityRevisionTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testValidConstruction( $dataItem, $lastRevId ) {
		$entityRevision = new EntityRevision( $dataItem, $lastRevId );
		$this->assertEquals( $dataItem, $entityRevision->getData() );
		$this->assertEquals( $lastRevId, $entityRevision->getLastRevId() );
	}

	public function provideValidConstruction() {
		$mockItem = $this->getMockBuilder( 'Wikibase\DataModel\Entity\Item' )
			->disableOriginalConstructor()
			->getMock();
		$mockProperty = $this->getMockBuilder( 'Wikibase\DataModel\Entity\Property' )
			->disableOriginalConstructor()
			->getMock();
		return array(
			array( $mockItem, 1234 ),
			array( $mockProperty, 1234 ),
		);
	}

} 