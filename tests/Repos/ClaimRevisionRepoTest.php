<?php

namespace Wikibase\Api\Test\Repos;

use Wikibase\Api\Repos\ClaimRevisionRepo;

/**
 * @covers Wikibase\Api\Repos\ClaimRevisionRepo
 */
class ClaimRevisionRepoTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new ClaimRevisionRepo( $this->getMockApi() );
		$this->assertTrue( true );
	}

} 