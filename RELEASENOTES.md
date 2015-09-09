These are the release notes for the [wikibase-api](README.md).

## Version 0.5.1 (2015-09-09)

* RevisionSaver now always sets the 'clear' param. Meaning elements can be removed from an entity.
* Added support for passing an `EditInfo` to all edit services.

## Version 0.5 (2015-06-29)

####Additions

* RevisionsGetter::getRevisions can now accept serialized EntityId strings
* Added BadgeIdsGetter service
* Added RedirectCreator service

####Deprecations

* Renamed ClaimGetter to StatementGetter, leaving a b/c deprecated alias
* Renamed ClaimSetter to StatementSetter, leaving a b/c deprecated alias
* Renamed ClaimRemover to StatementRemover, leaving a b/c deprecated alias
* Renamed ClaimCreator to StatementCreator, leaving a b/c deprecated alias
* Renamed WikibaseRepo::newClaimGetter to WikibaseRepo::newStatementGetter, leaving a b/c deprecated alias
* Renamed WikibaseRepo::newClaimSetter to WikibaseRepo::newStatementSetter, leaving a b/c deprecated alias
* Renamed WikibaseRepo::newClaimCreator to WikibaseRepo::newStatementCreator, leaving a b/c deprecated alias
* Renamed WikibaseRepo::newClaimRemover to WikibaseRepo::newStatementRemover, leaving a b/c deprecated alias

### Breaks

* Moved ItemApiLookup to Lookup namespace
* Moved PropertyApiLookup to Lookup namespace

####Libs

* NoLonger Require addwiki/mediawiki-api
* Requires "wikibase/data-model": "~4.0"
* Requires "wikibase/data-model-services": "~1.0"
* Requires "data-values/data-values": "~1.0.0" from "~0.1.0"
* Requires "data-values/time": "~0.8.0" from "~0.7.0"
* Requires "data-values/number": "~0.5.0" from "~0.4.0"
* Requires "data-values/common": "~0.3.0" from "~0.2.0"
* Requires "addwiki/mediawiki-api-base": "~0.3.0"
* Requires "addwiki/mediawiki-datamodel": "~0.5.0"

## Version 0.4 (2014-12-14)

* Added RevisionsGetter services for getting multiple revisions in as few requests as possible.
* Stop type hinting against deprecated Entity per https://lists.wikimedia.org/pipermail/wikidata-tech/2014-June/000489.html
* Requires "wikibase/data-model": "~3.0"

## Version 0.3 (2014-09-15)

####Fixes

* You can now clear an entity using RevisionSaver by passing an empty Item/Property/Entity object.
  Prior to this a UsageException would be thrown as the clear param was not passed to the api.
* SiteLinkSetter now uses the correct API module (wbsetsitelink).
  Previous to this it was for some reason trying to use 'wblinktitles'.

####Changes

* RevisionSaver->save() now returns an Entity object instead of always returning true.

####Libs

We now require the following:
* "wikibase/data-model-serialization": "~1.1"
* "wikibase/data-model": "~2.0|~1.0"
* "data-values/geo": "~1.0|~0.2.0"
* "data-values/data-types": "~0.4.0",


## Version 0.2 (2014-07-15)

* Adjust for mediawiki-api 0.3
* Adjust for mediawiki-datamodel 0.3
* Internal classes renamed
* Introduced a Service namespace and ServiceFactory
* Introduce Property and Item Content objects
* Renamed ServiceFactory to WikibaseFactory
* Renamed RevisionRepo to RevisionGetter
* Introduced a GenericOptions object
* Introduced several new services

## Version 0.1 (2014-02-23)

Initial release with the following features:

* EntityRevision
* RevisionRepo
* RevisionSaver
* ServiceFactory
* Can Get, Edit, Save, Create, Manipulate Entities
