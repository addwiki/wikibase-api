<?php

// Aliases introduced in 0.5
class_alias( 'Wikibase\Api\Service\StatementGetter', 'Wikibase\Api\Service\ClaimGetter' );
class_alias( 'Wikibase\Api\Service\StatementSetter', 'Wikibase\Api\Service\ClaimSetter' );
class_alias( 'Wikibase\Api\Service\StatementRemover', 'Wikibase\Api\Service\ClaimRemover' );
class_alias( 'Wikibase\Api\Service\StatementCreator', 'Wikibase\Api\Service\ClaimCreator' );