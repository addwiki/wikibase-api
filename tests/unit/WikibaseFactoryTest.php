<?php

namespace Wikibase\Api\Test;

use Wikibase\Api\WikibaseFactory;

/**
 * @covers Wikibase\Api\WikibaseFactory
 */
class WikibaseFactoryTest extends \PHPUnit_Framework_TestCase {

	private function getMockApi() {
		$mock = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		return $mock;
	}

	public function testValidConstructionWorks() {
		new WikibaseFactory( $this->getMockApi() );
		$this->assertTrue( true );
	}

	public function provideMethodsAndClasses() {
		return array(
			array( 'newAliasGroupSetter','\Wikibase\Api\Service\AliasGroupSetter' ),
			array( 'newClaimCreator','\Wikibase\Api\Service\ClaimCreator' ),
			array( 'newClaimGetter','\Wikibase\Api\Service\ClaimGetter' ),
			array( 'newClaimRemover','\Wikibase\Api\Service\ClaimRemover' ),
			array( 'newClaimSetter','\Wikibase\Api\Service\ClaimSetter' ),
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
		$factory = new WikibaseFactory( $this->getMockApi() );

		$this->assertTrue( method_exists( $factory, $method ) );
		$object = $factory->$method();
		$this->assertInstanceOf( $class, $object );
	}

} 