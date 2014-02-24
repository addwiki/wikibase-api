<?php

namespace Wikibase\Api\Test;

use Wikibase\Api\Service\RevisionRepo;

/**
 * @covers Wikibase\Api\RevisionRepo
 */
class RevisionRepoTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function getMockDeserializer() {
		$mock = $this->getMockBuilder( '\Deserializers\Deserializer' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new RevisionRepo( $this->getMockApi(), $this->getMockDeserializer() );
		$this->assertTrue( true );
	}

} 