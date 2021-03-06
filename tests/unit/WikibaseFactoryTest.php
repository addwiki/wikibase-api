<?php

namespace Addwiki\Wikibase\Tests\Unit;

use Addwiki\Mediawiki\Api\Client\Action\ActionApi;
use Addwiki\Wikibase\Api\Service\AliasGroupSetter;
use Addwiki\Wikibase\Api\Service\DescriptionSetter;
use Addwiki\Wikibase\Api\Service\ItemMerger;
use Addwiki\Wikibase\Api\Service\LabelSetter;
use Addwiki\Wikibase\Api\Service\ReferenceRemover;
use Addwiki\Wikibase\Api\Service\ReferenceSetter;
use Addwiki\Wikibase\Api\Service\RevisionGetter;
use Addwiki\Wikibase\Api\Service\RevisionSaver;
use Addwiki\Wikibase\Api\Service\SiteLinkLinker;
use Addwiki\Wikibase\Api\Service\SiteLinkSetter;
use Addwiki\Wikibase\Api\Service\StatementCreator;
use Addwiki\Wikibase\Api\Service\StatementGetter;
use Addwiki\Wikibase\Api\Service\StatementRemover;
use Addwiki\Wikibase\Api\Service\StatementSetter;
use Addwiki\Wikibase\Api\Service\ValueFormatter;
use Addwiki\Wikibase\Api\Service\ValueParser;
use Addwiki\Wikibase\Api\WikibaseFactory;
use Deserializers\Deserializer;
use PHPUnit\Framework\TestCase;
use Serializers\Serializer;

/**
 * @covers Wikibase\Api\WikibaseFactory
 */
class WikibaseFactoryTest extends TestCase {

	public function provideMethodsAndClasses(): array {
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
	public function testNewFactoryObject( string $method, string $class ): void {
		/** @var Serializer $dvSerializer */
		$dvSerializer = $this->createMock( Serializer::class );
		/** @var Deserializer $dvDeserializer */
		$dvDeserializer = $this->createMock( Deserializer::class );

		$factory = new WikibaseFactory( $this->createMock( ActionApi::class ), $dvDeserializer, $dvSerializer );

		$this->assertTrue( method_exists( $factory, $method ) );
		$object = $factory->$method();
		$this->assertInstanceOf( $class, $object );
	}

}
