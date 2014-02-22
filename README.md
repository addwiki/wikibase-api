wikibase-api
==================
[![Build Status](https://travis-ci.org/addwiki/wikibase-api.png?branch=master)](https://travis-ci.org/addwiki/wikibase-api)
[![Code Coverage](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/coverage.png?s=ca6d4e50e3ce5b9937a24928d8762af31d4e108c)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/addwiki/wikibase-api/badges/quality-score.png?s=41faa1f91a7d359370de48c4dec28cdd5db47b0d)](https://scrutinizer-ci.com/g/addwiki/wikibase-api/)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/addwiki/wikibase-api/version.png)](https://packagist.org/packages/addwiki/wikibase-api)
[![Download count](https://poser.pugx.org/addwiki/wikibase-api/d/total.png)](https://packagist.org/packages/addwiki/wikibase-api)

Example Usage
------

```php
require_once( __DIR__ . "/vendor/autoload.php" );

$api = new \Mediawiki\Api\MediawikiApi( "http://www.wikidata.org/w/api.php" );
$repoFactory = new \Wikibase\Api\RepositoryFactory( $api );
$repo = $repoFactory->newEntityRevisionRepo();
$entityRev = $repo->getFromId( 'Q4115189' );
$entityRev->getData()->setLabel( 'en', 'test' );
$saver = new \Wikibase\Api\Savers\EntityRevisionSaver( $api );
$saver->save( $entityRev );
```