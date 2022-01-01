<?php

namespace Addwiki\Wikibase\Api;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 * Object holding options.
 */
final class GenericOptions {

	private array $options = [];

	public function __construct( array $options = [] ) {
		foreach ( array_keys( $options ) as $option ) {
			if ( !is_string( $option ) ) {
				throw new InvalidArgumentException( 'Option names need to be strings' );
			}
		}

		$this->options = $options;
	}

	/**
	 * Sets the value of the specified option.
	 *
	 * @param mixed $value
	 * @throws InvalidArgumentException
	 */
	public function setOption( string $option, $value ): void {
		if ( !is_string( $option ) ) {
			throw new InvalidArgumentException( 'Option name needs to be a string' );
		}

		$this->options[$option] = $value;
	}

	/**
	 * Returns the value of the specified option. If the option is not set,
	 * an InvalidArgumentException is thrown.
	 *
	 * @throws OutOfBoundsException
	 */
	public function getOption( string $option ) {
		if ( !array_key_exists( $option, $this->options ) ) {
			throw new OutOfBoundsException( sprintf( "Option '%s' has not been set so cannot be obtained", $option ) );
		}

		return $this->options[$option];
	}

	/**
	 * Returns if the specified option is set or not.
	 */
	public function hasOption( string $option ): bool {
		return array_key_exists( $option, $this->options );
	}

	/**
	 * Sets the value of an option to the provided default in case the option is not set yet.
	 *
	 * @param mixed $default
	 */
	public function defaultOption( string $option, $default ): void {
		if ( !$this->hasOption( $option ) ) {
			$this->setOption( $option, $default );
		}
	}

	/**
	 * Requires an option to be set.
	 * If it's not set, a RuntimeException is thrown.
	 *
	 * @throws RuntimeException
	 */
	public function requireOption( string $option ): void {
		if ( !$this->hasOption( $option ) ) {
			throw new RuntimeException( 'Required option"' . $option . '" is not set' );
		}
	}

	/**
	 * Returns the array of all options.
	 *
	 * @return mixed[]
	 */
	public function getOptions(): array {
		return $this->options;
	}

}
