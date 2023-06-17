<?php
/**
 * BP Classic Functions tests.
 *
 * @package bp-classic\tests\phpunit\testcases
 *
 * @since 1.0.0
 */

/**
 * @group functions
 */
class BP_Classic_Functions_UnitTestCase extends BP_UnitTestCase {
	/**
	 * @group bp_core_add_root_component
	 */
	public function test_bp_core_add_root_component() {
		$bp = buddypress();

		$slug             = 'foobar';
		$bp->foobar       = new stdClass();
		$bp->foobar->id   = $slug;
		$bp->foobar->slug = $slug;

		bp_core_add_root_component( $slug );

		$this->assertTrue( $bp->foobar->has_directory, true );
		$this->assertEquals( $bp->loaded_components[ $slug ], $bp->foobar->id );
	}
}
