<?php

namespace Wikibase\Api\Test;

use Deserializers\Deserializer;
use Mediawiki\Api\MediawikiApi;
use PHPUnit\Framework\TestCase;
use Serializers\Serializer;
use Wikibase\Api\Service\AliasGroupSetter;
use Wikibase\Api\Service\DescriptionSetter;
use Wikibase\Api\Service\ItemMerger;
use Wikibase\Api\Service\LabelSetter;
use Wikibase\Api\Service\ReferenceRemover;
use Wikibase\Api\Service\ReferenceSetter;
use Wikibase\Api\Service\RevisionGetter;
use Wikibase\Api\Service\RevisionSaver;
use Wikibase\Api\Service\SiteLinkLinker;
use Wikibase\Api\Service\SiteLinkSetter;
use Wikibase\Api\Service\StatementCreator;
use Wikibase\Api\Service\StatementGetter;
use Wikibase\Api\Service\StatementRemover;
use Wikibase\Api\Service\StatementSetter;
use Wikibase\Api\Service\ValueFormatter;
use Wikibase\Api\Service\ValueParser;
use Wikibase\Api\WikibaseFactory;

/**
 * @author Addshore
 *
 * @covers Wikibase\Api\WikibaseFactory
 */
class WikibaseFactoryTest extends TestCase {

	public function provideMethodsAndClasses() {
		return [
			[ 'newAliasGroupSetter',AliasGroupSetter::class ],
			[ 'newStatementCreator',StatementCreator::class ],
			[ 'newStatementGetter',StatementGetter::class ],
			[ 'newStatementRemover',StatementRemover::class ],
			[ 'newStatementSetter',StatementSetter::class ],
			[ 'newDescriptionSetter',DescriptionSetter::class ],
			[ 'newItemMerger',ItemMerger::class ],
			[ 'newLabelSetter',LabelSetter::class ],
			[ 'newReferenceRemover',ReferenceRemover::class ],
			[ 'newReferenceSetter',ReferenceSetter::class ],
			[ 'newRevisionGetter',RevisionGetter::class ],
			[ 'newRevisionSaver',RevisionSaver::class ],
			[ 'newSiteLinkLinker',SiteLinkLinker::class ],
			[ 'newSiteLinkSetter',SiteLinkSetter::class ],
			[ 'newValueFormatter',ValueFormatter::class ],
			[ 'newValueParser',ValueParser::class ],
		];
	}

	/**
	 * @dataProvider provideMethodsAndClasses
	 */
	public function testNewFactoryObject( $method, $class ) {
		/** @var Serializer $dvSerializer */
		$dvSerializer = $this->createMock( \Serializers\Serializer::class );
		/** @var Deserializer $dvDeserializer */
		$dvDeserializer = $this->createMock( \Deserializers\Deserializer::class );

		$factory = new WikibaseFactory( $this->createMock( MediawikiApi::class ), $dvDeserializer, $dvSerializer );

		$this->assertTrue( method_exists( $factory, $method ) );
		$object = $factory->$method();
		$this->assertInstanceOf( $class, $object );
	}

}
