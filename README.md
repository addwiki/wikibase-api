wikibase-api
==================
[![Build Status](https://travis-ci.org/addwiki/wikibase-api.png?branch=master)](https://travis-ci.org/addwiki/wikibase-api)
[![Code Coverage](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/coverage.png?s=ca6d4e50e3ce5b9937a24928d8762af31d4e108c)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/quality-score.png?s=41faa1f91a7d359370de48c4dec28cdd5db47b0d)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)

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


## Example Usage

```php
// Load all of the things
require_once( __DIR__ . "/vendor/autoload.php" );

// Set stuff up and login
$api = new \Mediawiki\Api\MediawikiApi( "http://localhost/w/api.php" );
$api->login( new \Mediawiki\Api\ApiUser( 'username', 'password' ) );
$services = new \Wikibase\Api\WikibaseFactory( $api );
$getter = $services->newRevisionGetter();
$saver = $services->newRevisionSaver();

// Create a new Entity
$edit = new \Mediawiki\DataModel\Revision(
	new \Wikibase\DataModel\ItemContent( Wikibase\DataModel\Entity\Item::newEmpty() )
);
$saver->save( $edit );

// Set a label in the language en
$entityRevision = $getter->getFromId( 'Q87' );
$entityRevision->getContent()->getNativeData()->setDescription( 'en', 'I am A description' );
$saver->save( $entityRevision );

// Create a new string claim on an item if a claim for the property doesnt already exist
$revision = $services->newRevisionGetter()->getFromId( 'Q777' );
$item = $revision->getContent()->getNativeData();
$claims = new \Wikibase\DataModel\Claim\Claims( $item->getClaims() );
if( $claims->getClaimsForProperty( \Wikibase\DataModel\Entity\PropertyId::newFromNumber( 1320 ) )->isEmpty() ) {
	$services->newClaimCreator()->create(
		new \Wikibase\DataModel\Snak\PropertyValueSnak(
			\Wikibase\DataModel\Entity\PropertyId::newFromNumber( 1320 ),
			new \DataValues\StringValue( 'New String Value' )
		),
		'Q777'
	);
}

// Try to merge two items if possible, catch any errors
try{
	$services->newItemMerger()->merge( 'Q999', 'Q888' );
}
catch( \Mediawiki\Api\UsageException $e ) {
	echo "Oh no! I failed to merge!";
}
```
