wikibase-api
==================
[![Build Status](https://travis-ci.org/addwiki/wikibase-api.png?branch=master)](https://travis-ci.org/addwiki/wikibase-api)
[![Code Coverage](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/coverage.png?s=ca6d4e50e3ce5b9937a24928d8762af31d4e108c)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/quality-score.png?s=41faa1f91a7d359370de48c4dec28cdd5db47b0d)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/addwiki/wikibase-api/version.png)](https://packagist.org/packages/addwiki/wikibase-api)
[![Download count](https://poser.pugx.org/addwiki/wikibase-api/d/total.png)](https://packagist.org/packages/addwiki/wikibase-api)

Issue tracker: https://phabricator.wikimedia.org/project/profile/1490/

## Installation

Use one of the below methods:

1 - Use composer to install the library and all its dependencies using the master branch:

    composer require "addwiki/wikibase-api:dev-master"

2 - Create a composer.json file that just defines a dependency on version 0.7 of this package, and run 'composer install' in the directory:

    {
        "require": {
            "addwiki/wikibase-api": "~0.8.0"
        }
    }

The tests can be run as follows:

    phpunit -c tests/unit/

## Example Usage

The library provides users with a large collection of Services.
These services should be retrieved from the WikibaseFactory class.

Below you will find some more examples using various services.
Each example follows on from the previous example (so as not to repeat the first steps).
Note: this library uses namespaces so please remember to add the relevant [use clauses](http://php.net/manual/en/language.namespaces.importing.php).

#### Load & General Setup

```php
require_once( __DIR__ . '/vendor/autoload.php' );

$api = new MediawikiApi( 'http://localhost/w/api.php' );
$api->login( new ApiUser( 'username', 'password' ) );

// Create our Factory, All services should be used through this!
// You will need to add more or different datavalues here.
// In the future Wikidata / Wikibase defaults will be provided in seperate a library.
$dataValueClasses = array(
    'unknown' => 'DataValues\UnknownValue',
    'string' => 'DataValues\StringValue',
    'boolean' => 'DataValues\BooleanValue',
    'number' => 'DataValues\NumberValue',
    'globecoordinate' => 'DataValues\Geo\Values\GlobeCoordinateValue',
    'monolingualtext' => 'DataValues\MonolingualTextValue',
    'multilingualtext' => 'DataValues\MultilingualTextValue',
    'quantity' => 'DataValues\QuantityValue',
    'time' => 'DataValues\TimeValue',
    'wikibase-entityid' => 'Wikibase\DataModel\Entity\EntityIdValue',
);
$wbFactory = new WikibaseFactory(
    $api,
    new DataValues\Deserializers\DataValueDeserializer( $dataValueClasses ),
    new DataValues\Serializers\DataValueSerializer()
);
```

#### Create an empty entity

Create a new empty item.

```php
$saver = $wbFactory->newRevisionSaver();

$edit = new Revision(
    new ItemContent( Item::newEmpty() )
);
$resultingItem = $saver->save( $edit );

// You can get the ItemId object of the created item by doing the following
$itemId = $resultingItem->getId()
```

#### Set a label

Set an english label on the item Q87 assuming it exists, using a custom summary.

```php
$getter = $wbFactory->newRevisionGetter();

$entityRevision = $getter->getFromId( 'Q87' );
$entityRevision->getContent()->getData()->setDescription( 'en', 'I am A description' );
$saver->save( $entityRevision, new EditInfo( 'Custom edit summary' ) );
```

#### Create a new statement

Create a new string statement on item Q777 if a statement for the property doesn't already exist.

```php
$statementCreator = $wbFactory->newStatementCreator();
$revision = $getter->getFromId( 'Q777' );
$item = $revision->getContent()->getData();
$statementList = $item->getStatements();
if( $statementList->getByPropertyId( PropertyId::newFromNumber( 1320 ) )->isEmpty() ) {
    $statementCreator->create(
        new PropertyValueSnak(
            PropertyId::newFromNumber( 1320 ),
            new StringValue( 'New String Value' )
        ),
        'Q777'
    );
}
```

#### Remove a statement using a GUID

Remove the statement with the given claim (if it exists)

```php
$statementRemover = $wbFactory->newStatementRemover();

$statementRemover->remove( 'Q123$f12bd80f-415a-c37e-9e18-234b9e19eece' );
```

#### Add a reference to a statement

```php
$statementSetter = $wbFactory->newStatementSetter();
$revision = $getter->getFromId( 'Q9956' );
$item = $revision->getContent()->getData();
$statementList = $item->getStatements();
$referenceSnaks = array(
    new PropertyValueSnak( new PropertyId( 'P44' ), new StringValue( 'bar' ) ),
);
foreach( $statementList->getByPropertyId( PropertyId::newFromNumber( 99 ) )->getIterator() as $statement ) {
    if( $statement->getReferences()->isEmpty() ) {
        $statement->addNewReference( $referenceSnaks );
    }
}
```

#### Attempt to merge 2 items

Try to merge Q999 and Q888 if possible, catch any errors.

```php
try{
    $wbFactory->newItemMerger()->merge( 'Q999', 'Q888' );
}
catch( UsageException $e ) {
    echo "Oh no! I failed to merge!";
}
```

#### Simple Lookups

Easily lookup an item object, an individual label and redirect sources.

```php
$itemId = new ItemId( 'Q555' )
$itemLookup = $wbFactory->newItemLookup();
$termLookup = $wbFactory->newTermLookup();
$entityRedirectLookup = $wbFactory->newEntityRedirectLookup();

$item = $itemLookup->getItemForId( $itemId );
$enLabel = $termLookup->getLabel( $itemId, 'en' );
$redirectSources = $entityRedirectLookup->getRedirectIds( $itemId );
```
