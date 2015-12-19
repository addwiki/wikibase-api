<?php

namespace Wikibase\Api\DataModel\Test;

use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\ItemContent;

/**
 * @covers Wikibase\DataModel\ItemContent
 * @author Addshore
 */
class ItemContentTest extends \PHPUnit_Framework_TestCase {

	public function testModel() {
		$this->assertEquals( 'wikibase-item', ItemContent::MODEL );
	}

	public function testValidConstruction() {
		$item = new Item();
		$content = new ItemContent( $item );
		$this->assertEquals( $item, $content->getData() );
		$this->assertInternalType( 'string', $content->getHash() );
	}

}
