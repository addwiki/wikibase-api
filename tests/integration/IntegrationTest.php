<?php

namespace Wikibase\Api\Test;


use Mediawiki\Api\UsageException;
use Mediawiki\DataModel\Revision;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\ItemContent;
use Wikibase\DataModel\SiteLink;
use Wikibase\DataModel\Term\AliasGroup;
use Wikibase\DataModel\Term\Term;

class IntegrationTest extends IntegrationTestBase {

	/**
	 * This test requires a wiki installed at localhost that can be edited by anon users
	 * This test also requires the sites table to be populated using populateSitesTable.php
	 */
	public function test() {
		// Create a starting item
		$localItem = Item::newEmpty();
		$localItem->getFingerprint()->getLabels()->setTextForLanguage( 'en', 'TestItem - ' . strval( time() ) );
		$localItem->getFingerprint()->getDescriptions()->setTextForLanguage( 'en', 'TestDescription - ' . microtime() );

		// Save the item
		$newItem = $this->factory->newRevisionSaver()->save( new Revision( new ItemContent( $localItem ) ) );
		$itemId = $newItem->getId(); // Save our ID for later use
		$localItem->setId( $itemId );
		$this->assertTrue( $localItem->equals( $newItem ) );

		// Make sure the RevisionGetter will also return the same Item as expected
		$gotItem = $this->factory->newRevisionGetter()->getFromId( $itemId )->getContent()->getNativeData();
		$this->assertTrue( $localItem->equals( $gotItem ) );

		// Set a label in de
		$labelDe = new Term( 'de', 'Foo' . microtime() );
		$r = $this->factory->newLabelSetter()->set( $labelDe , $itemId );
		$this->assertTrue( $r );
		$localItem->getFingerprint()->getLabels()->setTerm( $labelDe );

		// Set a desc in en-gb
		$descDe = new Term( 'de', 'FooBarDesc' . microtime() );
		$r = $this->factory->newDescriptionSetter()->set( $descDe, $itemId );
		$this->assertTrue( $r );
		$localItem->getFingerprint()->getDescriptions()->setTerm( $descDe );

		// Set 2 aliases in fr
		try{
			$aliasFr = new AliasGroup( 'fr', array( 'aa', 'bb' ) );
			$r = $this->factory->newAliasGroupSetter()->set( $aliasFr, $itemId );
			$this->assertTrue( $r );
			$localItem->getFingerprint()->getAliasGroups()->setGroup( $aliasFr );
		}
		catch( UsageException $e ) {
			// Wikibase bug
		}

		// Set a sitelink for enwiki
		$enwikiLondon = new SiteLink( 'enwiki', 'London' );
		$r = $this->factory->newSiteLinkSetter()->set( $enwikiLondon, $itemId );
		$this->assertTrue( $r );
		$localItem->getSiteLinkList()->addSiteLink( $enwikiLondon );

		// Link another sitelink using the last
		$dewikiBerlin = new Sitelink( 'dewiki', 'Berlin' );
		$r = $this->factory->newSiteLinkLinker()->link( $enwikiLondon, $dewikiBerlin );
		$this->assertTrue( $r );
		$localItem->getSiteLinkList()->addSiteLink( $dewikiBerlin );

		// Empty the item
		$localItem = Item::newEmpty();
		$localItem->setId( $itemId );
		$newItem = $this->factory->newRevisionSaver()->save( new Revision( new ItemContent( $localItem ) ) );
		$this->assertTrue( $localItem->equals( $newItem ) );
	}

}
