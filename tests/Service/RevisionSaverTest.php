<?php

namespace Wikibase\Api\Test\Service;

use Wikibase\Api\Service\RevisionSaver;

/**
 * @covers Wikibase\Api\Service\RevisionSaver
 */
class RevisionSaverTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new RevisionSaver( $this->getMockApi() );
		$this->assertTrue( true );
	}

} 