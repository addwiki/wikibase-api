wikibase-api
==================
[![Build Status](https://travis-ci.org/addwiki/wikibase-api.png?branch=master)](https://travis-ci.org/addwiki/wikibase-api)
[![Code Coverage](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/coverage.png?s=ca6d4e50e3ce5b9937a24928d8762af31d4e108c)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/quality-score.png?s=41faa1f91a7d359370de48c4dec28cdd5db47b0d)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)
[![Dependency Status](https://www.versioneye.com/php/addwiki:wikibase-api/dev-master/badge.svg)](https://www.versioneye.com/php/addwiki:wikibase-api/dev-master)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/addwiki/wikibase-api/version.png)](https://packagist.org/packages/addwiki/wikibase-api)
[![Download count](https://poser.pugx.org/addwiki/wikibase-api/d/total.png)](https://packagist.org/packages/addwiki/wikibase-api)

Issue tracker: https://phabricator.wikimedia.org/project/profile/1490/

## Installation

Use one of the below methods:

1 - Use composer to install the library and all its dependencies using the master branch:

    composer require "addwiki/wikibase-api:dev-master"

2 - Create a composer.json file that just defines a dependency on version 0.2 of this package, and run 'composer install' in the directory:

    {
        "require": {
            "addwiki/wikibase-api": "~0.7.0"
        }
    }

The tests can be run as follows:

    phpunit -c tests/unit/

## Example Usage

Below you will find some more examples using various parts of the code.

Please also take a look at our integration tests that might be able to help you!

```php
use Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\ApiUser;
use Wikibase\Api\WikibaseFactory;
use Mediawiki\DataModel\Revision;
use Wikibase\DataModel\ItemContent;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Entity\PropertyId;
use DataValues\StringValue;
use UsageException;

// Load all of the things
require_once( __DIR__ . "/vendor/autoload.php" );

// Use the mediawiki api and Login
$api = new MediawikiApi( "http://localhost/w/api.php" );
$api->login( new ApiUser( 'username', 'password' ) );

//

// Create our Factory, All services should be used through this!
// If the wikibase you are accessing uses more or different datavalues they must be added here.
$dataValueClasses = array(
	'unknown' => 'DataValues\UnknownValue',
	'string' => 'DataValues\StringValue',
);
$services = new WikibaseFactory(
	$api,
	new DataValues\Deserializers\DataValueDeserializer( $dataValueClasses ),
	new DataValues\Serializers\DataValueSerializer()
);

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
$entityRevision->getContent()->getData()->setDescription( 'en', 'I am A description' );
$saver->save( $entityRevision );

// Create a new string statement on item Q777 if a statement for the property doesn't already exist
$revision = $services->newRevisionGetter()->getFromId( 'Q777' );
$item = $revision->getContent()->getData();
$statementList = $item->getStatements();
if( $statementList->getByPropertyId( PropertyId::newFromNumber( 1320 ) )->isEmpty() ) {
	$services->newStatementCreator()->create(
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
