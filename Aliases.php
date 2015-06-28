<?php

// This is a IDE helper to understand class aliasing.
// It should not be included anywhere.
// Actual aliasing happens in the entry point using class_alias.

namespace { throw new Exception( 'This code is not meant to be executed' ); }

namespace Wikibase\Api\Service {

	/**
	 * @deprecated since 0.5, use the base class instead.
	 */
	class ClaimGetter extends StatementGetter {}
	/**
	 * @deprecated since 0.5, use the base class instead.
	 */
	class ClaimSetter extends StatementSetter {}
	/**
	 * @deprecated since 0.5, use the base class instead.
	 */
	class ClaimRemover extends StatementRemover {}
	/**
	 * @deprecated since 0.5, use the base class instead.
	 */
	class ClaimCreator extends StatementCreator {}

}
