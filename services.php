<?php
/**
 * Services
 *
 * @package WC_HK_Post
 */

return array(
	'loc_ord' => array(
		'file'  => 'postageRate-local-ORD.json',
		'title' => __( 'Local Mail', 'shipping-rates-for-hk-post' ),
		'label' => __( 'Local Mail (Packet)', 'shipping-rates-for-hk-post' ),
		'name'  => 'Local Mail (Packet)',
	),
	'loc_reg' => array(
		'file'  => 'postageRate-local-REG.json',
		'title' => __( 'Local Registered Mail', 'shipping-rates-for-hk-post' ),
		'label' => __( 'Local Registered Mail (Packet)', 'shipping-rates-for-hk-post' ),
		'name'  => 'Local Registered Mail (Packet)',
	),
	'loc_par' => array(
		'file'  => 'postageRate-local-PAR.json',
		'title' => __( 'Local Parcels', 'shipping-rates-for-hk-post' ),
		'label' => __( 'Local Parcels', 'shipping-rates-for-hk-post' ),
		'name'  => 'Local Parcels',
	),
	'loc_std' => array(
		'file'  => 'postageRate-local-LCP.json',
		'title' => __( 'Local CourierPost', 'shipping-rates-for-hk-post' ),
		'label' => __( 'Local CourierPost', 'shipping-rates-for-hk-post' ),
		'name'  => 'Local CourierPost',
	),
	'loc_smp' => array(
		'file'  => 'postageRate-local-SMP.json',
		'title' => __( 'Smart Post', 'shipping-rates-for-hk-post' ),
		'label' => __( 'Local Smart Post (Mail Delivery)', 'shipping-rates-for-hk-post' ),
		'name'  => 'Smart Post (Mail Delivery)',
	),
	// 'sur_ord' => array( 'label' => __( 'International Surface Mail (Packet)', 'shipping-rates-for-hk-post' ) ), // phpcs:ignore
	'sur_reg' => array(
		'file'  => 'postageRate-intl-REG.json',
		'title' => __( 'Surface Registered Mail', 'shipping-rates-for-hk-post' ),
		'label' => __( 'International Surface Registered Mail (Packet)', 'shipping-rates-for-hk-post' ),
		'name'  => 'Surface Registered Mail (Packet)',
	),
	// 'air_ord' => array( 'label' => __( 'International Air Mail (Packet)', 'shipping-rates-for-hk-post' ) ), // phpcs:ignore
	'air_reg' => array(
		'file'  => 'postageRate-intl-REG.json',
		'title' => __( 'Air Registered Mail', 'shipping-rates-for-hk-post' ),
		'label' => __( 'International Air Registered Mail (Packet)', 'shipping-rates-for-hk-post' ),
		'name'  => 'Air Registered Mail (Packet)',
	),
	'sur_par' => array(
		'file'  => 'postageRate-intl-SURPAR.json',
		'title' => __( 'Surface Parcel', 'shipping-rates-for-hk-post' ),
		'label' => __( 'International Surface Parcel', 'shipping-rates-for-hk-post' ),
		'name'  => 'Surface Parcel',
	),
	'air_par' => array(
		'file'  => 'postageRate-intl-AIRPAR.json',
		'title' => __( 'Air Parcel', 'shipping-rates-for-hk-post' ),
		'label' => __( 'International Air Parcel', 'shipping-rates-for-hk-post' ),
		'name'  => 'Air Parcel',
	),
	'spt_std' => array(
		'file'  => 'postageRate-intl-SPT.json',
		'title' => __( 'Speedpost', 'shipping-rates-for-hk-post' ),
		'label' => __( 'International Speedpost (Standard Service)', 'shipping-rates-for-hk-post' ),
		'name'  => 'Speedpost (Standard Service)',
	),
	'exp' => array(
		'file'  => 'postageRate-intl-EXP.json',
		'title' => __( 'e-Express Service', 'shipping-rates-for-hk-post' ),
		'label' => __( 'International e-Express', 'shipping-rates-for-hk-post' ),
		'name'  => 'e-Express Service',
	),
);
