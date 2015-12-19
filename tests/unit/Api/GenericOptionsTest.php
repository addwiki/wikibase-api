<?php

namespace Wikibase\Api\Test;

use Wikibase\Api\GenericOptions;

/**
 * @covers Wikibase\Api\GenericOptions
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Addshore
 */
class GenericOptionsTest extends \PHPUnit_Framework_TestCase {

	public function testConstructor() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			'baz' => array( 'o_O', false, null, '42' => 42, array() )
		);

		$genericOptions = new GenericOptions( $options );

		$this->assertEquals( $options, $genericOptions->getOptions() );
		$this->assertFalse( $genericOptions->hasOption( 'ohi' ) );
	}

	public function testConstructorFail() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			42 => array( 'o_O', false, null, '42' => 42, array() )
		);

		$this->setExpectedException( 'Exception' );

		new GenericOptions( $options );
	}

	public function setOptionProvider() {
		$argLists = array();

		$genericOptions = new GenericOptions();

		$argLists[] = array( $genericOptions, 'foo', 42 );
		$argLists[] = array( $genericOptions, 'bar', 42 );
		$argLists[] = array( $genericOptions, 'foo', 'foo' );
		$argLists[] = array( $genericOptions, 'foo', null );

		return $argLists;
	}

	/**
	 * @dataProvider setOptionProvider
	 *
	 * @param GenericOptions $options
	 * @param $option
	 * @param $value
	 */
	public function testSetAndGetOption( GenericOptions $options, $option, $value ) {
		$options->setOption( $option, $value );

		$this->assertEquals(
			$value,
			$options->getOption( $option ),
			'Setting an option should work'
		);
	}

	public function testHashOption() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			'baz' => array( 'o_O', false, null, '42' => 42, array() )
		);

		$genericOptions = new GenericOptions( $options );

		foreach ( array_keys( $options ) as $option ) {
			$this->assertTrue( $genericOptions->hasOption( $option ) );
		}

		$this->assertFalse( $genericOptions->hasOption( 'ohi' ) );
		$this->assertFalse( $genericOptions->hasOption( 'Foo' ) );
	}

	public function testSetOption() {
		$genericOptions = new GenericOptions( array( 'foo' => 'bar' ) );

		$values = array(
			array( 'foo', 'baz' ),
			array( 'foo', 'bar' ),
			array( 'onoez', '' ),
			array( 'hax', 'zor' ),
			array( 'nyan', 9001 ),
			array( 'cat', 4.2 ),
			array( 'spam', array( '~=[,,_,,]:3' ) ),
		);

		foreach ( $values as $value ) {
			$genericOptions->setOption( $value[0], $value[1] );
			$this->assertEquals( $value[1], $genericOptions->getOption( $value[0] ) );
		}
	}

	public function testForSomeReasonPhpSegfaultsIfThereIsOneMethodLess() {
		$this->assertTrue( (bool)'This is fucking weird' );
	}

	/**
	 * @dataProvider nonExistingOptionsProvider
	 */
	public function testGetOption( $nonExistingOption ) {
		$this->assertTrue( true );
		$genericOptions = new GenericOptions( array( 'foo' => 'bar' ) );

		$this->setExpectedException( 'OutOfBoundsException' );

		$genericOptions->getOption( $nonExistingOption );
	}

	public function nonExistingOptionsProvider() {
		$argLists = array();

		$argLists[] = array( 'bar' );
		$argLists[] = array( 'Foo' );
		$argLists[] = array( 'FOO' );
		$argLists[] = array( 'spam' );
		$argLists[] = array( 'onoez' );

		return $argLists;
	}

	public function testRequireOption() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			'baz' => array( 'o_O', false, null, '42' => 42, array() )
		);

		$genericOptions = new GenericOptions( $options );

		foreach ( array_keys( $options ) as $option ) {
			$genericOptions->requireOption( $option );
		}

		$this->setExpectedException( 'Exception' );

		$genericOptions->requireOption( 'Foo' );
	}

	public function testDefaultOption() {
		$options = array(
			'foo' => 42,
			'bar' => 4.2,
			'baz' => array( 'o_O', false, null, '42' => 42, array() )
		);

		$genericOptions = new GenericOptions( $options );

		foreach ( $options as $option => $value ) {
			$genericOptions->defaultOption( $option, 9001 );

			$this->assertEquals(
				serialize( $value ),
				serialize( $genericOptions->getOption( $option ) ),
				'Defaulting a set option should not affect its value'
			);
		}

		$defaults = array(
			'N' => 42,
			'y' => 4.2,
			'a' => false,
			'n' => array( '42' => 42, array( '' ) )
		);

		foreach ( $defaults as $option => $value ) {
			$genericOptions->defaultOption( $option, $value );

			$this->assertEquals(
				serialize( $value ),
				serialize( $genericOptions->getOption( $option ) ),
				'Defaulting a not set option should affect its value'
			);
		}
	}

}
