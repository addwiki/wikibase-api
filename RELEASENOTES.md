These are the release notes for the [wikibase-api](README.md).

## Version 0.4 (in development)

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
