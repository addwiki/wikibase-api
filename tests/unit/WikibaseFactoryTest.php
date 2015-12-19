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
class WikibaseFactoryTest extends \PHPUnit_Framework_TestCase {

	public function provideMethodsAndClasses() {
		return array(
			array( 'newAliasGroupSetter','\Wikibase\Api\Service\AliasGroupSetter' ),
			array( 'newStatementCreator','\Wikibase\Api\Service\StatementCreator' ),
			array( 'newStatementGetter','\Wikibase\Api\Service\StatementGetter' ),
			array( 'newStatementRemover','\Wikibase\Api\Service\StatementRemover' ),
			array( 'newStatementSetter','\Wikibase\Api\Service\StatementSetter' ),
			array( 'newDescriptionSetter','\Wikibase\Api\Service\DescriptionSetter' ),
			array( 'newItemMerger','\Wikibase\Api\Service\ItemMerger' ),
			array( 'newLabelSetter','\Wikibase\Api\Service\LabelSetter' ),
			array( 'newReferenceRemover','\Wikibase\Api\Service\ReferenceRemover' ),
			array( 'newReferenceSetter','\Wikibase\Api\Service\ReferenceSetter' ),
			array( 'newRevisionGetter','\Wikibase\Api\Service\RevisionGetter' ),
			array( 'newRevisionSaver','\Wikibase\Api\Service\RevisionSaver' ),
			array( 'newSiteLinkLinker','\Wikibase\Api\Service\SiteLinkLinker' ),
			array( 'newSiteLinkSetter','\Wikibase\Api\Service\SiteLinkSetter' ),
			array( 'newValueFormatter','\Wikibase\Api\Service\ValueFormatter' ),
			array( 'newValueParser','\Wikibase\Api\Service\ValueParser' ),
		);
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