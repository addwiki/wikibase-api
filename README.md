wikibase-api
==================
[![Build Status](https://travis-ci.org/addwiki/wikibase-api.png?branch=master)](https://travis-ci.org/addwiki/wikibase-api)
[![Code Coverage](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/coverage.png?s=ca6d4e50e3ce5b9937a24928d8762af31d4e108c)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/quality-score.png?s=41faa1f91a7d359370de48c4dec28cdd5db47b0d)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)
[![Dependency Status](https://www.versioneye.com/php/addwiki:wikibase-api/dev-master/badge.svg)](https://www.versioneye.com/php/addwiki:wikibase-api/dev-master)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/addwiki/wikibase-api/version.png)](https://packagist.org/packages/addwiki/wikibase-api)
[![Download count](https://poser.pugx.org/addwiki/wikibase-api/d/total.png)](https://packagist.org/packages/addwiki/wikibase-api)

## Installation

Use one of the below methods:

1 - Use composer to install the library and all its dependencies using the master branch:

    composer require "addwiki/wikibase-api:dev-master"

2 - Create a composer.json file that just defines a dependency on version 0.2 of this package, and run 'composer install' in the directory:

    {
        "require": {
            "addwiki/wikibase-api": "~0.2.0"
        }
    }

The tests can be run as follows:

    phpunit -c tests/unit/

## Example Usage

Below you will find some smore examples using various parts of the code.

Please also take a look at our integration tests that might be able to help you!

```php
// Load all of the things
require_once( __DIR__ . "/vendor/autoload.php" );

use DataValues\StringValue;
use Mediawiki\Api\ApiUser;
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\UsageException;
use MediaWiki\DataModel\Revision;
use Wikibase\Api\WikibaseFactory;
use Wikibase\DataModel\Claim\Claims;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\ItemContent;
use Wikibase\DataModel\Snak\PropertyValueSnak;

// Use the mediawiki api and Login
$api = new MediawikiApi( "http://localhost/w/api.php" );
$api->login( new ApiUser( 'username', 'password' ) );

// Create our Factory, All services should be used through this!
$services = new WikibaseFactory( $api );

// Get 2 specific services
$getter = $services->newRevisionGetter();
$saver = $services->newRevisionSaver();

// Create a new Entity
$edit = new Revision(
	new ItemContent( Item::newEmpty() )
);
$saver->save( $edit );

// Set a label in the language en on the item Q87
$entityRevision = $getter->getFromId( 'Q87' );
$entityRevision->getContent()->getNativeData()->setDescription( 'en', 'I am A description' );
$saver->save( $entityRevision );

// Create a new string claim on item Q777 if a claim for the property doesn't already exist
$revision = $services->newRevisionGetter()->getFromId( 'Q777' );
$item = $revision->getContent()->getNativeData();
$claims = new Claims( $item->getClaims() );
if( $claims->getClaimsForProperty( PropertyId::newFromNumber( 1320 ) )->isEmpty() ) {
	$services->newClaimCreator()->create(
		new PropertyValueSnak(
			PropertyId::newFromNumber( 1320 ),
			new StringValue( 'New String Value' )
		),
		'Q777'
	);
}

// Try to merge Q999 and Q888 if possible, catch any errors
try{
	$services->newItemMerger()->merge( 'Q999', 'Q888' );
}
catch( UsageException $e ) {
	echo "Oh no! I failed to merge!";
}
```
