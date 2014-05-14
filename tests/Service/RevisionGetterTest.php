<?php

namespace Wikibase\Api\Test;

use Wikibase\Api\Service\RevisionGetter;

/**
 * @covers Wikibase\Api\Service\RevisionGetter
 */
class RevisionGetterTest extends \PHPUnit_Framework_TestCase {

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
		new RevisionGetter( $this->getMockApi(), $this->getMockDeserializer() );
		$this->assertTrue( true );
	}

} 