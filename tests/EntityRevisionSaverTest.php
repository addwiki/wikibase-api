<?php

namespace Wikibase\Api\Test;

use Wikibase\Api\EntityRevisionSaver;

/**
 * @covers Wikibase\Api\EntityRevisionSaver
 */
class EntityRevisionSaverTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new EntityRevisionSaver( $this->getMockApi() );
		$this->assertTrue( true );
	}

} 