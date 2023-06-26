<?php

/**
 * @group core
 * @group nav
 * @expectedIncorrectUsage bp_nav
 */
class BP_Classic_Legacy_Nav_BackCompat extends BP_UnitTestCase {
	protected $bp_nav;
	protected $bp_options_nav;
	protected $permalink_structure = '';

	public function set_up() {
		parent::set_up();
		$this->bp_nav = buddypress()->bp_nav;
		$this->bp_options_nav = buddypress()->bp_options_nav;
		$this->permalink_structure = get_option( 'permalink_structure', '' );
	}

	public function tear_down() {
		buddypress()->bp_nav = $this->bp_nav;
		buddypress()->bp_options_nav = $this->bp_options_nav;
		$this->set_permalink_structure( $this->permalink_structure );
		parent::tear_down();
	}

	protected function create_nav_items() {
		bp_core_new_nav_item( array(
			'name'                => 'Foo',
			'slug'                => 'foo',
			'position'            => 25,
			'screen_function'     => 'foo_screen_function',
			'default_subnav_slug' => 'foo-subnav'
		) );

		bp_core_new_subnav_item( array(
			'name'            => 'Foo Subnav',
			'slug'            => 'foo-subnav',
			'parent_url'      => 'example.com/foo',
			'parent_slug'     => 'foo',
			'screen_function' => 'foo_screen_function',
			'position'        => 10
		) );
	}

	/**
	 * Create a group, set up nav item, and go to the group.
	 */
	protected function set_up_group() {
		$this->set_permalink_structure( '/%postname%/' );
		$g = self::factory()->group->create( array(
			'slug' => 'testgroup',
		) );

		$group = groups_get_group( $g );
		$group_permalink = bp_get_group_url( $group );

		$this->go_to( $group_permalink );

		bp_core_new_subnav_item( array(
			'name'            => 'Foo',
			'slug'            => 'foo',
			'parent_url'      => $group_permalink,
			'parent_slug'     => 'testgroup',
			'screen_function' => 'foo_screen_function',
			'position'        => 10
		), 'groups' );
	}

	public function test_bp_nav_isset() {
		$this->create_nav_items();

		$bp = buddypress();

		$this->assertTrue( isset( $bp->bp_nav ) );
		$this->assertTrue( isset( $bp->bp_nav['foo'] ) );
		$this->assertTrue( isset( $bp->bp_nav['foo']['name'] ) );
	}

	public function test_bp_nav_unset() {
		$this->create_nav_items();

		$bp = buddypress();

		// No support for this - it would create a malformed nav item.
		/*
		unset( $bp->bp_nav['foo']['css_id'] );
		$this->assertFalse( isset( $bp->bp_nav['foo']['css_id'] ) );
		*/

		unset( $bp->bp_nav['foo'] );
		$this->assertFalse( isset( $bp->bp_nav['foo'] ) );
	}

	public function test_bp_nav_get() {
		$this->create_nav_items();

		$bp = buddypress();

		$foo = $bp->bp_nav['foo'];
		$this->assertSame( 'Foo', $foo['name'] );

		$this->assertSame( 'Foo', $bp->bp_nav['foo']['name'] );
	}

	public function test_bp_nav_set() {
		$this->create_nav_items();

		$bp = buddypress();

		$bp->bp_nav['foo']['name'] = 'Bar';

		$nav = bp_get_nav_menu_items();

		foreach ( $nav as $_nav ) {
			if ( 'foo' === $_nav->css_id ) {
				$found = $_nav;
				break;
			}
		}

		$this->assertSame( 'Bar', $found->name );
	}

	public function test_bp_options_nav_isset() {
		$this->create_nav_items();

		$bp = buddypress();

		$this->assertTrue( isset( $bp->bp_options_nav ) );
		$this->assertTrue( isset( $bp->bp_options_nav['foo'] ) );
		$this->assertTrue( isset( $bp->bp_options_nav['foo']['foo-subnav'] ) );
		$this->assertTrue( isset( $bp->bp_options_nav['foo']['foo-subnav']['name'] ) );
	}

	public function test_bp_options_nav_unset() {
		$this->create_nav_items();

		$bp = buddypress();

		// No support for this - it would create a malformed nav item.
		/*
		unset( $bp->bp_options_nav['foo']['foo-subnav']['user_has_access'] );
		$this->assertFalse( isset( $bp->bp_options_nav['foo']['foo-subnav']['user_has_access'] ) );
		*/

		unset( $bp->bp_options_nav['foo']['foo-subnav'] );
		$this->assertFalse( isset( $bp->bp_options_nav['foo']['foo-subnav'] ) );

		// Make sure the parent nav hasn't been wiped out.
		$this->assertTrue( isset( $bp->bp_options_nav['foo'] ) );

		unset( $bp->bp_options_nav['foo'] );
		$this->assertFalse( isset( $bp->bp_options_nav['foo'] ) );
	}

	public function test_bp_options_nav_get() {
		$this->create_nav_items();

		$bp = buddypress();

		$foo_subnav = $bp->bp_options_nav['foo']['foo-subnav'];
		$this->assertSame( 'Foo Subnav', $foo_subnav['name'] );

		$this->assertSame( 'Foo Subnav', $bp->bp_options_nav['foo']['foo-subnav']['name'] );
	}

	public function test_bp_options_nav_set() {
		$this->create_nav_items();

		$bp = buddypress();

		$bp->bp_options_nav['foo']['foo-subnav']['name'] = 'Bar';
		$nav = bp_get_nav_menu_items();

		foreach ( $nav as $_nav ) {
			if ( 'foo-subnav' === $_nav->css_id ) {
				$found = $_nav;
				break;
			}
		}

		$this->assertSame( 'Bar', $found->name );

		$subnav = array(
			'name' => 'Bar',
			'css_id' => 'bar-id',
			'link' => 'bar-link',
			'slug' => 'bar-slug',
			'user_has_access' => true,
		);
		$bp->bp_options_nav['foo']['foo-subnav'] = $subnav;
		$nav = bp_get_nav_menu_items();

		foreach ( $nav as $_nav ) {
			if ( 'bar-id' === $_nav->css_id ) {
				$found = $_nav;
				break;
			}
		}

		$this->assertSame( 'Bar', $found->name );
	}

	/**
	 * @group groups
	 */
	public function test_bp_options_nav_isset_group_nav() {
		$this->markTestSkipped();
		$this->set_up_group();

		$bp = buddypress();

		$this->assertTrue( isset( $bp->bp_options_nav ) );
		$this->assertTrue( isset( $bp->bp_options_nav['testgroup'] ) );
		$this->assertTrue( isset( $bp->bp_options_nav['testgroup']['foo'] ) );
		$this->assertTrue( isset( $bp->bp_options_nav['testgroup']['foo']['name'] ) );
	}

	/**
	 * @group groups
	 */
	public function test_bp_options_nav_unset_group_nav() {
		$this->markTestSkipped();
		$this->set_up_group();

		$bp = buddypress();

		// No support for this - it would create a malformed nav item.
		/*
		unset( $bp->bp_options_nav['testgroup']['foo']['user_has_access'] );
		$this->assertFalse( isset( $bp->bp_options_nav['testgroup']['foo']['user_has_access'] ) );
		*/

		unset( $bp->bp_options_nav['testgroup']['foo'] );
		$this->assertFalse( isset( $bp->bp_options_nav['testgroup']['foo'] ) );

		unset( $bp->bp_options_nav['testgroup'] );
		$this->assertFalse( isset( $bp->bp_options_nav['testgroup'] ) );
	}

	/**
	 * @group groups
	 */
	public function test_bp_options_nav_get_group_nav() {
		$this->markTestSkipped();
		$this->set_up_group();

		$bp = buddypress();

		$foo = $bp->bp_options_nav['testgroup']['foo'];
		$this->assertSame( 'Foo', $foo['name'] );

		$this->assertSame( 'Foo', $bp->bp_options_nav['testgroup']['foo']['name'] );
	}

	/**
	 * @group groups
	 */
	public function test_bp_options_nav_set_group_nav() {
		$this->markTestSkipped();
		$this->set_up_group();

		$bp = buddypress();

		$bp->bp_options_nav['testgroup']['foo']['name'] = 'Bar';
		$nav = bp_get_nav_menu_items( 'groups' );

		foreach ( $nav as $_nav ) {
			if ( 'foo' === $_nav->css_id ) {
				$found = $_nav;
				break;
			}
		}

		$this->assertSame( 'Bar', $found->name );

		$subnav = array(
			'name' => 'Bar',
			'css_id' => 'bar-id',
			'link' => 'bar-link',
			'slug' => 'bar-slug',
			'user_has_access' => true,
		);
		$bp->bp_options_nav['testgroup']['foo'] = $subnav;
		$nav = bp_get_nav_menu_items( 'groups' );

		foreach ( $nav as $_nav ) {
			if ( 'bar-id' === $_nav->css_id ) {
				$found = $_nav;
				break;
			}
		}

		$this->assertSame( 'Bar', $found->name );
	}

	/**
	 * @group groups
	 */
	public function test_bp_core_new_subnav_item_should_work_in_group_context() {
		$this->markTestSkipped();
		$this->set_up_group();

		bp_core_new_subnav_item( array(
			'name' => 'Foo Subnav',
			'slug' => 'foo-subnav',
			'parent_slug' => bp_get_current_group_slug(),
			'parent_url' => bp_get_group_url( groups_get_current_group() ),
			'screen_function' => 'foo_subnav',
		) );

		$bp = buddypress();

		// Touch bp_nav since we told PHPUnit it was expectedDeprecated.
		$f = $bp->bp_options_nav[ bp_get_current_group_slug() ];

		$nav = bp_get_nav_menu_items( 'groups' );

		foreach ( $nav as $_nav ) {
			if ( 'foo-subnav' === $_nav->css_id ) {
				$found = $_nav;
				break;
			}
		}

		$this->assertSame( 'Foo Subnav', $found->name );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_user_nav() {
		$this->markTestSkipped();
		$bp_nav = buddypress()->bp_nav;

		$u = self::factory()->user->create();
		$old_current_user = get_current_user_id();
		$this->set_current_user( $u );
		$this->set_permalink_structure( '/%postname%/' );

		$this->go_to( bp_members_get_user_url( $u ) );

		bp_core_new_nav_item( array(
			'name'                    => 'Foo',
			'slug'                    => 'foo',
			'position'                => 25,
			'screen_function'         => 'foo_screen_function',
			'default_subnav_slug'     => 'foo-sub'
		) );

		$expected = array(
			'name'                    => 'Foo',
			'slug'                    => 'foo',
			'link'                    => bp_members_get_user_url(
				$u,
				array(
					'single_item_component' => 'foo',
				)
			),
			'css_id'                  => 'foo',
			'show_for_displayed_user' => true,
			'position'                => 25,
			'screen_function'         => 'foo_screen_function',
			'default_subnav_slug'     => 'foo-sub'
		);

		foreach ( $expected as $k => $v ) {
			$this->assertEquals( $v, buddypress()->bp_nav['foo'][ $k ] );
		}

		// Clean up
		buddypress()->bp_nav = $bp_nav;
		$this->set_current_user( $old_current_user );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_group_nav() {
		$this->markTestSkipped();
		$bp_nav = buddypress()->bp_nav;

		$u = self::factory()->user->create();
		$g = self::factory()->group->create();
		$old_current_user = get_current_user_id();
		$this->set_current_user( $u );
		$this->set_permalink_structure( '/%postname%/' );

		$group = groups_get_group( $g );

		$this->go_to( bp_get_group_url( $group ) );

		$this->assertTrue( buddypress()->bp_nav[ $group->slug ]['position'] === -1 );

		// Clean up
		buddypress()->bp_nav = $bp_nav;
		$this->set_current_user( $old_current_user );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_css_id_should_fall_back_on_slug() {
		$args = array(
			'name' => 'Foo',
			'slug' => 'foo',
		);
		bp_core_new_nav_item( $args );

		$this->assertSame( 'foo', buddypress()->bp_nav['foo']['css_id'] );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_css_id_should_be_respected() {
		$args = array(
			'name' => 'Foo',
			'slug' => 'foo',
			'item_css_id' => 'bar',
		);
		bp_core_new_nav_item( $args );

		$this->assertSame( 'bar', buddypress()->bp_nav['foo']['css_id'] );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_existence_of_access_protected_user_nav() {
		$this->markTestSkipped();
		$bp_nav = buddypress()->bp_nav;

		$u = self::factory()->user->create();
		$u2 = self::factory()->user->create();
		$old_current_user = get_current_user_id();
		$this->set_current_user( $u2 );
		$this->set_permalink_structure( '/%postname%/' );

		$this->go_to( bp_members_get_user_url( $u ) );

		$expected = array(
			'name'                    => 'Settings',
			'slug'                    => 'settings',
			'link'                    => bp_members_get_user_url(
				$u,
				array(
					'single_item_component' => 'settings',
				)
			),
			'css_id'                  => 'settings',
			'show_for_displayed_user' => false,
			'position'                => 100,
			'screen_function'         => 'bp_settings_screen_general',
			'default_subnav_slug'     => 'general'
		);

		foreach ( $expected as $k => $v ) {
			$this->assertEquals( $v, buddypress()->bp_nav['settings'][ $k ] );
		}

		// Clean up
		buddypress()->bp_nav = $bp_nav;
		$this->set_current_user( $old_current_user );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_creation_of_access_protected_user_nav() {
		$this->markTestSkipped();
		// The nav item must be added to bp_nav, even if the current user
		// can't visit that nav item.
		$bp_nav = buddypress()->bp_nav;

		$u = self::factory()->user->create();
		$u2 = self::factory()->user->create();
		$old_current_user = get_current_user_id();
		$this->set_current_user( $u2 );
		$this->set_permalink_structure( '/%postname%/' );

		$this->go_to( bp_members_get_user_url( $u ) );

		bp_core_new_nav_item( array(
			'name'                    => 'Woof',
			'slug'                    => 'woof',
			'show_for_displayed_user' => false,
			'position'                => 35,
			'screen_function'         => 'woof_screen_function',
			'default_subnav_slug'     => 'woof-one'
		) );

		$expected = array(
			'name'                    => 'Woof',
			'slug'                    => 'woof',
			'link'                    => bp_members_get_user_url(
				$u,
				array(
					'single_item_component' => 'woof',
				)
			),
			'css_id'                  => 'woof',
			'show_for_displayed_user' => false,
			'position'                => 35,
			'screen_function'         => 'woof_screen_function',
			'default_subnav_slug'     => 'woof-one'
		);

		foreach ( $expected as $k => $v ) {
			$this->assertEquals( $v, buddypress()->bp_nav['woof'][ $k ] );
		}

		// Clean up
		buddypress()->bp_nav = $bp_nav;
		$this->set_current_user( $old_current_user );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_user_subnav() {
		$this->set_permalink_structure( '/%postname%/' );
		$bp_options_nav = buddypress()->bp_options_nav;

		$u = self::factory()->user->create();
		$old_current_user = get_current_user_id();
		$this->set_current_user( $u );

		$this->go_to( bp_members_get_user_url( $u ) );

		bp_core_new_nav_item( array(
			'name'            => 'Foo Parent',
			'slug'            => 'foo-parent',
			'screen_function' => 'foo_screen_function',
			'position'        => 10,
		) );

		bp_core_new_subnav_item( array(
			'name'            => 'Foo',
			'slug'            => 'foo',
			'parent_url'      => bp_members_get_user_url(
				$u,
				array(
					'single_item_component' => 'foo-parent',
				)
			),
			'parent_slug'     => 'foo-parent',
			'screen_function' => 'foo_screen_function',
			'position'        => 10
		) );

		$expected = array(
			'name'              => 'Foo',
			'link'              => bp_members_get_user_url(
				$u,
				array(
					'single_item_component' => 'foo-parent',
					'single_item_action'    => 'foo',
				)
			),
			'slug'              => 'foo',
			'css_id'            => 'foo',
			'position'          => 10,
			'user_has_access'   => true,
			'no_access_url'     => '',
			'screen_function'   => 'foo_screen_function',
			'show_in_admin_bar' => false,
		);

		foreach ( $expected as $k => $v ) {
			$this->assertSame( $v, buddypress()->bp_options_nav['foo-parent']['foo'][ $k ] );
		}

		// Clean up
		buddypress()->bp_options_nav = $bp_options_nav;
		$this->set_current_user( $old_current_user );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_link_provided() {
		$bp_options_nav = buddypress()->bp_options_nav;

		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'screen_function' => 'foo',
			'link' => 'https://buddypress.org/',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'bar',
			'slug' => 'bar',
			'parent_slug' => 'foo',
			'parent_url' => 'foo',
			'screen_function' => 'foo',
			'link' => 'https://buddypress.org/',
		) );

		$this->assertSame( 'https://buddypress.org/', buddypress()->bp_options_nav['foo']['bar']['link'] );

		buddypress()->bp_options_nav = $bp_options_nav;
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_link_built_from_parent_url_and_slug() {
		$bp_options_nav = buddypress()->bp_options_nav;

		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'screen_function' => 'foo',
			'link' => 'https://buddypress.org/',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'bar',
			'slug' => 'bar',
			'parent_slug' => 'foo',
			'parent_url' => 'http://example.com/foo/',
			'screen_function' => 'foo',
		) );

		$this->assertSame( 'http://example.com/foo/bar/', buddypress()->bp_options_nav['foo']['bar']['link'] );

		buddypress()->bp_options_nav = $bp_options_nav;
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_link_built_from_parent_url_and_slug_where_slug_is_default() {
		$bp_nav = buddypress()->bp_nav;
		$bp_options_nav = buddypress()->bp_options_nav;

		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'url' => 'http://example.com/foo/',
			'screen_function' => 'foo',
			'default_subnav_slug' => 'bar',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'bar',
			'slug' => 'bar',
			'parent_slug' => 'foo',
			'parent_url' => 'http://example.com/foo/',
			'screen_function' => 'foo',
		) );

		$this->assertSame( 'http://example.com/foo/bar/', buddypress()->bp_options_nav['foo']['bar']['link'] );

		// clean up
		buddypress()->bp_nav = $bp_nav;
		buddypress()->bp_options_nav = $bp_options_nav;
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_should_trailingslash_link_when_link_is_autogenerated_using_slug() {
		$this->set_permalink_structure( '/%postname%/' );
		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'screen_function' => 'foo',
			'link' => 'https://buddypress.org/',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'bar',
			'slug' => 'bar',
			'parent_slug' => 'foo',
			'parent_url' => bp_get_root_url() . 'foo/',
			'screen_function' => 'foo',
		) );

		$expected = bp_get_root_url() . 'foo/bar/';
		$this->assertSame( $expected, buddypress()->bp_options_nav['foo']['bar']['link'] );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_should_trailingslash_link_when_link_is_autogenerated_not_using_slug() {
		$this->set_permalink_structure( '/%postname%/' );
		bp_core_new_nav_item( array(
			'name' => 'foo',
			'slug' => 'foo-parent',
			'link' => bp_get_root_url() . 'foo-parent/',
			'default_subnav_slug' => 'bar',
			'screen_function' => 'foo',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'bar',
			'slug' => 'bar',
			'parent_slug' => 'foo-parent',
			'parent_url' => bp_get_root_url() . '/foo-parent/',
			'screen_function' => 'bar',
		) );

		$expected = bp_get_root_url() . '/foo-parent/bar/';
		$this->assertSame( $expected, buddypress()->bp_options_nav['foo-parent']['bar']['link'] );
	}

	/**
	 * @ticket BP6353
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_link_should_not_trailingslash_link_explicit_link() {
		$link = 'http://example.com/foo/bar/blah/?action=edit&id=30';

		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'screen_function' => 'foo',
			'link' => 'http://example.com/foo/',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'bar',
			'slug' => 'bar',
			'parent_slug' => 'foo',
			'parent_url' => 'http://example.com/foo/',
			'screen_function' => 'foo',
			'link' => $link,
		) );

		$this->assertSame( $link, buddypress()->bp_options_nav['foo']['bar']['link'] );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_css_id_should_fallback_on_slug() {
		bp_core_new_nav_item( array(
			'name' => 'Parent',
			'slug' => 'parent',
			'screen_function' => 'foo',
		) );

		$args = array(
			'name' => 'Foo',
			'slug' => 'foo',
			'parent_slug' => 'parent',
			'parent_url' => bp_get_root_url() . '/parent/',
			'screen_function' => 'foo',
		);
		bp_core_new_subnav_item( $args );

		$this->assertSame( 'foo', buddypress()->bp_options_nav['parent']['foo']['css_id'] );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_css_id_should_still_be_respected() {
		bp_core_new_nav_item( array(
			'name' => 'Parent',
			'slug' => 'parent',
			'screen_function' => 'foo',
		) );

		$args = array(
			'name' => 'Foo',
			'slug' => 'foo',
			'parent_slug' => 'parent',
			'parent_url' => bp_get_root_url() . '/parent/',
			'screen_function' => 'foo',
			'item_css_id' => 'bar',
		);
		bp_core_new_subnav_item( $args );

		$this->assertSame( 'bar', buddypress()->bp_options_nav['parent']['foo']['css_id'] );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_should_remove_subnav_items() {
		$bp = buddypress();

		$_bp_nav = $bp->bp_nav;
		$_bp_options_nav = $bp->bp_options_nav;

		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'screen_function' => 'foo',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'Bar',
			'slug' => 'bar',
			'parent_slug' => 'foo',
			'parent_url' => 'foo',
			'screen_function' => 'bar',
		) );

		$this->assertTrue( isset( $bp->bp_nav['foo'] ) );
		$this->assertTrue( isset( $bp->bp_options_nav['foo'] ) );
		$this->assertTrue( isset( $bp->bp_options_nav['foo']['bar'] ) );

		bp_core_remove_nav_item( 'foo' );

		$this->assertFalse( isset( $bp->bp_options_nav['foo']['bar'] ) );

		$bp->bp_nav = $_bp_nav;
		$bp->bp_options_nav = $_bp_options_nav;
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_should_remove_nav_item() {
		$bp = buddypress();

		$_bp_nav = $bp->bp_nav;
		$_bp_options_nav = $bp->bp_options_nav;

		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'screen_function' => 'foo',
		) );

		$this->assertTrue( isset( $bp->bp_nav['foo'] ) );

		bp_core_remove_nav_item( 'foo' );

		$this->assertFalse( isset( $bp->bp_nav['foo'] ) );

		$bp->bp_nav = $_bp_nav;
		$bp->bp_options_nav = $_bp_options_nav;
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_should_remove_subnav_item() {
		$bp = buddypress();

		$_bp_nav = $bp->bp_nav;
		$_bp_options_nav = $bp->bp_options_nav;

		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'screen_function' => 'foo',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'Bar',
			'slug' => 'bar',
			'parent_slug' => 'foo',
			'parent_url' => 'foo',
			'screen_function' => 'bar',
		) );

		$this->assertTrue( isset( $bp->bp_options_nav['foo']['bar'] ) );

		bp_core_remove_subnav_item( 'foo', 'bar' );

		$this->assertFalse( isset( $bp->bp_options_nav['foo']['bar'] ) );

		$bp->bp_nav = $_bp_nav;
		$bp->bp_options_nav = $_bp_options_nav;
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_should_fail_on_incorrect_parent() {
		$bp = buddypress();

		$_bp_nav = $bp->bp_nav;
		$_bp_options_nav = $bp->bp_options_nav;

		bp_core_new_nav_item( array(
			'name' => 'Foo',
			'slug' => 'foo',
			'screen_function' => 'foo',
		) );

		bp_core_new_subnav_item( array(
			'name' => 'Bar',
			'slug' => 'bar',
			'parent_slug' => 'foo',
			'parent_url' => 'foo',
			'screen_function' => 'bar',
		) );

		$this->assertTrue( isset( $bp->bp_options_nav['foo']['bar'] ) );

		bp_core_remove_subnav_item( 'bad-parent', 'bar' );

		$this->assertTrue( isset( $bp->bp_options_nav['foo']['bar'] ) );

		$bp->bp_nav = $_bp_nav;
		$bp->bp_options_nav = $_bp_options_nav;
	}

	/**
	 * @group enable_nav_item
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_enable_nav_item_true() {
		$this->markTestSkipped();
		$old_options_nav = buddypress()->bp_options_nav;
		$this->set_permalink_structure( '/%postname%/' );

		$g = self::factory()->group->create();
		$g_obj = groups_get_group( $g );

		$class_name = 'BPTest_Group_Extension_Enable_Nav_Item_True';
		$e = new $class_name();

		$this->go_to( bp_get_group_url( $g_obj ) );

		$e->_register();

		$this->assertTrue( isset( buddypress()->bp_options_nav[ $g_obj->slug ][ $e->slug ] ) );

		// Clean up
		buddypress()->bp_options_nav = $old_options_nav;
	}

	/**
	 * @group enable_nav_item
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_enable_nav_item_false() {
		$this->markTestSkipped();
		$this->set_permalink_structure( '/%postname%/' );
		$old_options_nav = buddypress()->bp_options_nav;

		$g = self::factory()->group->create();
		$g_obj = groups_get_group( $g );

		$class_name = 'BPTest_Group_Extension_Enable_Nav_Item_False';
		$e = new $class_name();

		$this->go_to( bp_get_group_url( $g_obj ) );

		$e->_register();

		$this->assertFalse( isset( buddypress()->bp_options_nav[ $g_obj->slug ][ $e->slug ] ) );

		// Clean up
		buddypress()->bp_options_nav = $old_options_nav;
	}

	/**
	 * @group visibility
	 * @expectedIncorrectUsage bp_nav
	 */
	public function test_visibility_private() {
		$this->markTestSkipped();
		$this->set_permalink_structure( '/%postname%/' );
		$old_options_nav = buddypress()->bp_options_nav;
		$old_current_user = get_current_user_id();

		$g = self::factory()->group->create( array(
			'status' => 'private',
		) );
		$g_obj = groups_get_group( $g );

		$class_name = 'BPTest_Group_Extension_Visibility_Private';
		$e = new $class_name();

		// Test as non-logged-in user
		$this->set_current_user( 0 );
		$this->go_to( bp_get_group_url( $g_obj ) );
		$e->_register();
		$this->assertFalse( isset( buddypress()->bp_options_nav[ $g_obj->slug ][ $e->slug ] ) );

		// Clean up
		buddypress()->bp_options_nav = $old_options_nav;

		// Test as group member
		$u = self::factory()->user->create();
		$this->set_current_user( $u );
		$this->add_user_to_group( $u, $g );
		$this->go_to( bp_get_group_url( $g_obj ) );
		$e->_register();
		$this->assertTrue( isset( buddypress()->bp_options_nav[ $g_obj->slug ][ $e->slug ] ) );

		// Clean up
		buddypress()->bp_options_nav = $old_options_nav;
		$this->set_current_user( $old_current_user );
	}

	/**
	 * @group visibility
	 * @expectedIncorrectUsage bp_nav
	 *
	 * visibility=public + status=private results in adding the item to
	 * the nav. However, BP_Groups_Component::setup_globals() bounces the
	 * user away from this page on a regular pageload (BP 2.0 and under)
	 *
	 * @see https://buddypress.trac.wordpress.org/ticket/4785
	 */
	public function test_visibility_public() {
		$this->markTestSkipped();
		$this->set_permalink_structure( '/%postname%/' );
		$old_options_nav = buddypress()->bp_options_nav;
		$old_current_user = get_current_user_id();

		$g = self::factory()->group->create( array(
			'status' => 'private',
		) );
		$g_obj = groups_get_group( $g );

		$class_name = 'BPTest_Group_Extension_Visibility_Public';
		$e = new $class_name();

		// Test as non-logged-in user
		$this->set_current_user( 0 );
		$this->go_to( bp_get_group_url( $g_obj ) );
		$e->_register();
		$this->assertTrue( isset( buddypress()->bp_options_nav[ $g_obj->slug ][ $e->slug ] ) );

		// Clean up
		buddypress()->bp_options_nav = $old_options_nav;

		// Test as group member
		$u = self::factory()->user->create();
		$this->set_current_user( $u );
		$this->add_user_to_group( $u, $g );
		$this->go_to( bp_get_group_url( $g_obj ) );
		$e->_register();
		$this->assertTrue( isset( buddypress()->bp_options_nav[ $g_obj->slug ][ $e->slug ] ) );

		// Clean up
		buddypress()->bp_options_nav = $old_options_nav;
		$this->set_current_user( $old_current_user );
	}

	/**
	 * @expectedIncorrectUsage bp_nav
	 */
	function test_nav_menu() {
		$this->markTestSkipped();
		$this->set_permalink_structure( '/%postname%/' );
		$this->go_to( '/' );
		$this->assertTrue( isset( buddypress()->bp_nav['activity'] ) );
		$this->assertTrue( isset( buddypress()->bp_nav['profile'] ) );
	}
}
