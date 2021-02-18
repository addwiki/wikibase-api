<?php

namespace Addwiki\Wikibase\Tests\Unit\DataModel;

use Addwiki\Wikibase\DataModel\ItemContent;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\Item;

/**
 * @covers Wikibase\DataModel\ItemContent
 * @author Addshore
 */
class ItemContentTest extends TestCase {

	public function testModel() {
		$this->assertEquals( 'wikibase-item', ItemContent::MODEL );
	}

	public function testValidConstruction() {
		$item = new Item();
		$content = new ItemContent( $item );
		$this->assertEquals( $item, $content->getData() );
		$this->assertIsString( $content->getHash() );
	}

}
