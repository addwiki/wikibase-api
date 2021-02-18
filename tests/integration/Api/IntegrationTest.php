<?php

namespace Addwiki\Wikibase\Tests\Integration\Api;

use Addwiki\Mediawiki\DataModel\Revision;
use Addwiki\Wikibase\DataModel\ItemContent;
use Addwiki\Wikibase\Tests\Integration\TestEnvironment;
use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\SiteLink;
use Wikibase\DataModel\Term\AliasGroup;
use Wikibase\DataModel\Term\Term;

/**
 * This test requires a wiki installed at localhost that can be edited by anon users
 * This test also requires the sites table to be populated using populateSitesTable.php
 *
 * @author Addshore
 */
class IntegrationTest extends TestCase {

	/**
	 * @var ItemId
	 */
	private static $itemId;

	/**
	 * @var Item
	 */
	private static $localItem;

	public static function setUpBeforeClass() : void {
		parent::setUpBeforeClass();
		self::$localItem = new Item();
		self::$localItem->getFingerprint()->getLabels()->setTextForLanguage( 'en', 'TestItem - ' . strval( time() ) );
		self::$localItem->getFingerprint()->getDescriptions()->setTextForLanguage( 'en', 'TestDescription - ' . microtime() );
	}

	public function testCreateItem() {
		$factory = TestEnvironment::newDefault()->getFactory();

		$newItem = $factory->newRevisionSaver()->save( new Revision( new ItemContent( self::$localItem ) ) );
		self::$itemId = $newItem->getId(); // Save our ID for later use
		self::$localItem->setId( self::$itemId );
		$this->assertTrue( self::$localItem->equals( $newItem ) );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testGetNewlyCreatedItem() {
		$factory = TestEnvironment::newDefault()->getFactory();
		// Make sure the RevisionGetter will also return the same Item as expected
		$gotItem = $factory->newRevisionGetter()->getFromId( self::$itemId )->getContent()->getData();
		$this->assertTrue( self::$localItem->equals( $gotItem ) );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetLabel() {
		$factory = TestEnvironment::newDefault()->getFactory();

		$labelDe = new Term( 'de', 'Foo' . microtime() );
		$r = $factory->newLabelSetter()->set( $labelDe, self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getFingerprint()->getLabels()->setTerm( $labelDe );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetDescription() {
		$factory = TestEnvironment::newDefault()->getFactory();

		$descDe = new Term( 'de', 'FooBarDesc' . microtime() );
		$r = $factory->newDescriptionSetter()->set( $descDe, self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getFingerprint()->getDescriptions()->setTerm( $descDe );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetAliases() {
		$factory = TestEnvironment::newDefault()->getFactory();

		$aliasFr = new AliasGroup( 'fr', [ 'aa', 'bb' ] );
		$r = $factory->newAliasGroupSetter()->set( $aliasFr, self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getFingerprint()->getAliasGroups()->setGroup( $aliasFr );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetSitelink() {
		$factory = TestEnvironment::newDefault()->getFactory();

		$enwikiLondon = new SiteLink( 'mywiki', 'Main Page' );
		// Expect an exception as we didn't actually setup the test site fully
		$this->expectExceptionMessage( 'The external client site "mywiki" did not provide page information for page "Main Page"' );
		$factory->newSiteLinkSetter()->set( $enwikiLondon, self::$itemId );
	}

	/**
	 * @depends testCreateItem
	 * @depends testSetSitelink
	 */
	public function testLinkSitelinks() {
		$factory = TestEnvironment::newDefault()->getFactory();

		$enwikiLondon = new SiteLink( 'mywiki', 'Main Page' );
		$dewikiBerlin = new Sitelink( 'dewiki', 'Main Page' );
		// Expect an exception as we didn't actually setup dewiki as a test site
		$this->expectExceptionMessage( 'Unrecognized value for parameter "fromsite": dewiki.' );
		$factory->newSiteLinkLinker()->link( $enwikiLondon, $dewikiBerlin );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testEmptyItem() {
		$factory = TestEnvironment::newDefault()->getFactory();

		self::$localItem = new Item();

		self::$localItem->setId( self::$itemId );

		$newItem = $factory->newRevisionSaver()->save( new Revision( new ItemContent( self::$localItem ) ) );
		$this->assertTrue( self::$localItem->equals( $newItem ) );
	}

}
