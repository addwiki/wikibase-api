<?php

namespace Wikibase\Api\DataModel\Test;

use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\PropertyContent;

/**
 * @covers Wikibase\DataModel\PropertyContent
 * @author Addshore
 */
class PropertyContentTest extends \PHPUnit_Framework_TestCase {

	public function testModel() {
		$this->assertEquals( 'wikibase-property', PropertyContent::MODEL );
	}

	public function testValidConstruction() {
		$property = Property::newFromType( 'string' );
		$content = new PropertyContent( $property );
		$this->assertEquals( $property, $content->getData() );
		$this->assertInternalType( 'string', $content->getHash() );
	}

}
