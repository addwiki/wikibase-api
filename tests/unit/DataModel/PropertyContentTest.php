<?php

namespace Addwiki\Wikibase\Tests\Unit\DataModel;

use Addwiki\Wikibase\DataModel\PropertyContent;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\Property;

/**
 * @covers Wikibase\DataModel\PropertyContent
 * @author Addshore
 */
class PropertyContentTest extends TestCase {

	public function testModel(): void {
		$this->assertEquals( 'wikibase-property', PropertyContent::MODEL );
	}

	public function testValidConstruction(): void {
		$property = Property::newFromType( 'string' );
		$content = new PropertyContent( $property );
		$this->assertEquals( $property, $content->getData() );
		$this->assertIsString( $content->getHash() );
	}

}
