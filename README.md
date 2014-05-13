wikibase-api
==================
[![Build Status](https://travis-ci.org/addwiki/wikibase-api.png?branch=master)](https://travis-ci.org/addwiki/wikibase-api)
[![Code Coverage](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/coverage.png?s=ca6d4e50e3ce5b9937a24928d8762af31d4e108c)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/quality-score.png?s=41faa1f91a7d359370de48c4dec28cdd5db47b0d)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/addwiki/wikibase-api/version.png)](https://packagist.org/packages/addwiki/wikibase-api)
[![Download count](https://poser.pugx.org/addwiki/wikibase-api/d/total.png)](https://packagist.org/packages/addwiki/wikibase-api)

## Installation

Use composer to install the library and all its dependencies:

    composer require "addwiki/wikibase-api:dev-master"


Example Usage
------

```php
// Load all of the things
require_once( __DIR__ . "/vendor/autoload.php" );

// Set stuff up and login
$api = new \Mediawiki\Api\MediawikiApi( "http://localhost/w/api.php" );
$api->login( new \Mediawiki\Api\ApiUser( 'username', 'password' ) );
$services = new \Wikibase\Api\ServiceFactory( $api );
$repo = $services->newRevisionRepo();
$saver = $services->newRevisionSaver();

// Create a new Entity
$edit = new \Mediawiki\DataModel\Revision(
	new \Wikibase\DataModel\ItemContent( Wikibase\DataModel\Entity\Item::newEmpty() )
);
$saver->save( $edit );

// Edit an existing Entity
$entityRevision = $repo->getFromId( 'Q87' );
$entityRevision->getContent()->getNativeData()->setDescription( 'en', 'I am A description' );
$saver->save( $entityRevision );
```