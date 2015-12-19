<?php

namespace Wikibase\Api\Test;

use Mediawiki\DataModel\Revision;
use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\ItemContent;
use Wikibase\DataModel\SiteLink;
use Wikibase\DataModel\Term\AliasGroup;
use Wikibase\DataModel\Term\Term;

/**
 * This test requires a wiki installed at localhost that can be edited by anon users
 * This test also requires the sites table to be populated using populateSitesTable.php
 *
 * @author Addshore
 */
class IntegrationTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var ItemId
	 */
	static private $itemId;

	/**
	 * @var Item
	 */
	static private $localItem;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		self::$localItem = new Item();
		self::$localItem->getFingerprint()->getLabels()->setTextForLanguage( 'en', 'TestItem - ' . strval( time() ) );
		self::$localItem->getFingerprint()->getDescriptions()->setTextForLanguage( 'en', 'TestDescription - ' . microtime() );
	}

	public function testCreateItem() {
		$factory = $factory = TestEnvironment::newDefault()->getFactory();
		$newItem = $factory->newRevisionSaver()->save( new Revision( new ItemContent( self::$localItem ) ) );
		self::$itemId = $newItem->getId(); // Save our ID for later use
		self::$localItem->setId( self::$itemId );
		$this->assertTrue( self::$localItem->equals( $newItem ) );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testGetNewlyCreatedItem() {
		$factory = $factory = TestEnvironment::newDefault()->getFactory();
		// Make sure the RevisionGetter will also return the same Item as expected
		$gotItem = $factory->newRevisionGetter()->getFromId( self::$itemId )->getContent()->getData();
		$this->assertTrue( self::$localItem->equals( $gotItem ) );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetLabel() {
		$factory = $factory = TestEnvironment::newDefault()->getFactory();
		$labelDe = new Term( 'de', 'Foo' . microtime() );
		$r = $factory->newLabelSetter()->set( $labelDe , self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getFingerprint()->getLabels()->setTerm( $labelDe );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetDescription() {
		$factory = $factory = TestEnvironment::newDefault()->getFactory();
		$descDe = new Term( 'de', 'FooBarDesc' . microtime() );
		$r = $factory->newDescriptionSetter()->set( $descDe, self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getFingerprint()->getDescriptions()->setTerm( $descDe );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetAliases() {
		$factory = $factory = TestEnvironment::newDefault()->getFactory();
		$aliasFr = new AliasGroup( 'fr', array( 'aa', 'bb' ) );
		$r = $factory->newAliasGroupSetter()->set( $aliasFr, self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getFingerprint()->getAliasGroups()->setGroup( $aliasFr );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetSitelink() {
		$factory = $factory = TestEnvironment::newDefault()->getFactory();
		$enwikiLondon = new SiteLink( 'enwiki', 'London' );
		$r = $factory->newSiteLinkSetter()->set( $enwikiLondon, self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getSiteLinkList()->addSiteLink( $enwikiLondon );
	}

	/**
	 * @depends testCreateItem
	 * @depends testSetSitelink
	 */
	public function testLinkSitelinks() {
		$factory = $factory = TestEnvironment::newDefault()->getFactory();
		$enwikiLondon = new SiteLink( 'enwiki', 'London' );
		$dewikiBerlin = new Sitelink( 'dewiki', 'Berlin' );
		$r = $factory->newSiteLinkLinker()->link( $enwikiLondon, $dewikiBerlin );
		$this->assertTrue( $r );
		self::$localItem->getSiteLinkList()->addSiteLink( $dewikiBerlin );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testEmptyItem() {
		$factory = $factory = TestEnvironment::newDefault()->getFactory();
		self::$localItem = new Item();
		self::$localItem->setId( self::$itemId );
		$newItem = $factory->newRevisionSaver()->save( new Revision( new ItemContent( self::$localItem ) ) );
		$this->assertTrue( self::$localItem->equals( $newItem ) );
	}

}
