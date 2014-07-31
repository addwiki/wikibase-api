<?php

namespace Wikibase\Api\Test;


use Mediawiki\Api\UsageException;
use Mediawiki\DataModel\Revision;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\ItemContent;
use Wikibase\DataModel\SiteLink;
use Wikibase\DataModel\Term\AliasGroup;
use Wikibase\DataModel\Term\Term;


/**
 * This test requires a wiki installed at localhost that can be edited by anon users
 * This test also requires the sites table to be populated using populateSitesTable.php
 */
class IntegrationTest extends IntegrationTestBase {

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
		self::$localItem = Item::newEmpty();
		self::$localItem->getFingerprint()->getLabels()->setTextForLanguage( 'en', 'TestItem - ' . strval( time() ) );
		self::$localItem->getFingerprint()->getDescriptions()->setTextForLanguage( 'en', 'TestDescription - ' . microtime() );
	}

	public function testCreateItem() {
		$newItem = $this->factory->newRevisionSaver()->save( new Revision( new ItemContent( self::$localItem ) ) );
		self::$itemId = $newItem->getId(); // Save our ID for later use
		self::$localItem->setId( self::$itemId );
		$this->assertTrue( self::$localItem->equals( $newItem ) );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testGetNewlyCreatedItem() {
		// Make sure the RevisionGetter will also return the same Item as expected
		$gotItem = $this->factory->newRevisionGetter()->getFromId( self::$itemId )->getContent()->getNativeData();
		$this->assertTrue( self::$localItem->equals( $gotItem ) );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetLabel() {
		$labelDe = new Term( 'de', 'Foo' . microtime() );
		$r = $this->factory->newLabelSetter()->set( $labelDe , self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getFingerprint()->getLabels()->setTerm( $labelDe );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetDescription() {
		$descDe = new Term( 'de', 'FooBarDesc' . microtime() );
		$r = $this->factory->newDescriptionSetter()->set( $descDe, self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getFingerprint()->getDescriptions()->setTerm( $descDe );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetAliases() {
		try{
			$aliasFr = new AliasGroup( 'fr', array( 'aa', 'bb' ) );
			$r = $this->factory->newAliasGroupSetter()->set( $aliasFr, self::$itemId );
			$this->assertTrue( $r );
			self::$localItem->getFingerprint()->getAliasGroups()->setGroup( $aliasFr );
			$this->markTestIncomplete( 'This is no longer a bug!' );
		}
		catch( UsageException $e ) {
			// Wikibase bug
			$this->markTestSkipped( 'Wikibase bug: ' . $e->getMessage() );
		}
	}

	/**
	 * @depends testCreateItem
	 */
	public function testSetSitelink() {
		$enwikiLondon = new SiteLink( 'enwiki', 'London' );
		$r = $this->factory->newSiteLinkSetter()->set( $enwikiLondon, self::$itemId );
		$this->assertTrue( $r );
		self::$localItem->getSiteLinkList()->addSiteLink( $enwikiLondon );
	}

	/**
	 * @depends testCreateItem
	 * @depends testSetSitelink
	 */
	public function testLinkSitelinks() {
		$enwikiLondon = new SiteLink( 'enwiki', 'London' );
		$dewikiBerlin = new Sitelink( 'dewiki', 'Berlin' );
		$r = $this->factory->newSiteLinkLinker()->link( $enwikiLondon, $dewikiBerlin );
		$this->assertTrue( $r );
		self::$localItem->getSiteLinkList()->addSiteLink( $dewikiBerlin );
	}

	/**
	 * @depends testCreateItem
	 */
	public function testEmptyItem() {
		self::$localItem = Item::newEmpty();
		self::$localItem->setId( self::$itemId );
		$newItem = $this->factory->newRevisionSaver()->save( new Revision( new ItemContent( self::$localItem ) ) );
		$this->assertTrue( self::$localItem->equals( $newItem ) );
	}

}
