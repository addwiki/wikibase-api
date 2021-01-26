<?php

namespace Wikibase\Api\Test;

use Deserializers\Deserializer;
use Serializers\Serializer;
use Wikibase\Api\WikibaseFactory;

/**
 * @author Addshore
 *
 * @covers Wikibase\Api\WikibaseFactory
 */
class WikibaseFactoryTest extends \PHPUnit\Framework\TestCase {

	public function provideMethodsAndClasses() {
		return [
			[ 'newAliasGroupSetter','\Wikibase\Api\Service\AliasGroupSetter' ],
			[ 'newStatementCreator','\Wikibase\Api\Service\StatementCreator' ],
			[ 'newStatementGetter','\Wikibase\Api\Service\StatementGetter' ],
			[ 'newStatementRemover','\Wikibase\Api\Service\StatementRemover' ],
			[ 'newStatementSetter','\Wikibase\Api\Service\StatementSetter' ],
			[ 'newDescriptionSetter','\Wikibase\Api\Service\DescriptionSetter' ],
			[ 'newItemMerger','\Wikibase\Api\Service\ItemMerger' ],
			[ 'newLabelSetter','\Wikibase\Api\Service\LabelSetter' ],
			[ 'newReferenceRemover','\Wikibase\Api\Service\ReferenceRemover' ],
			[ 'newReferenceSetter','\Wikibase\Api\Service\ReferenceSetter' ],
			[ 'newRevisionGetter','\Wikibase\Api\Service\RevisionGetter' ],
			[ 'newRevisionSaver','\Wikibase\Api\Service\RevisionSaver' ],
			[ 'newSiteLinkLinker','\Wikibase\Api\Service\SiteLinkLinker' ],
			[ 'newSiteLinkSetter','\Wikibase\Api\Service\SiteLinkSetter' ],
			[ 'newValueFormatter','\Wikibase\Api\Service\ValueFormatter' ],
			[ 'newValueParser','\Wikibase\Api\Service\ValueParser' ],
		];
	}

	/**
	 * @dataProvider provideMethodsAndClasses
	 */
	public function testNewFactoryObject( $method, $class ) {
		/** @var Serializer $dvSerializer */
		$dvSerializer = $this->getMock( 'Serializers\Serializer' );
		/** @var Deserializer $dvDeserializer */
		$dvDeserializer = $this->getMock( 'Deserializers\Deserializer' );

		$factory = new WikibaseFactory( $this->getMockApi(), $dvDeserializer, $dvSerializer );

		$this->assertTrue( method_exists( $factory, $method ) );
		$object = $factory->$method();
		$this->assertInstanceOf( $class, $object );
	}

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

}
