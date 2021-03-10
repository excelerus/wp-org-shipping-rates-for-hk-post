<?php
/**
 * Class SampleTest
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	public function test_sample() {
		$this->assertTrue( 'Storefront' == wp_get_theme()->get( 'Name' ) );
        $this->assertTrue( is_plugin_active('shipping-rates-for-hk-post/shipping-rates-for-hk-post.php') );
	}
}
