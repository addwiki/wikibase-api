<?php

namespace Addwiki\Wikibase\DataModel;

use Addwiki\Mediawiki\DataModel\Content;
use Wikibase\DataModel\Entity\Item;

/**
 * @author Addshore
 */
class ItemContent extends Content {

	/**
	 * @var string
	 */
	public const MODEL = 'wikibase-item';

	/**
	 * @param Item $item
	 */
	public function __construct( Item $item ) {
		parent::__construct( $item, self::MODEL );
	}

	/**
	 * @required
	 * @see Content::getData
	 * @return Item
	 */
	public function getData() {
		return parent::getData();
	}
}
