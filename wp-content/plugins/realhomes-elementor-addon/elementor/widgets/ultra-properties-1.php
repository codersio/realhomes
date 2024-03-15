<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RHEA_Ultra_Properties_Widget_One extends \Elementor\Widget_Base {
	private $is_rvr_enabled;

	public function __construct( array $data = [], array $args = null ) {
		parent::__construct( $data, $args );
		$this->is_rvr_enabled = rhea_is_rvr_enabled();
	}

	public function get_name() {
		return 'rhea-ultra-properties-widget-1';
	}

	public function get_title() {
		return esc_html__( 'Ultra Properties', 'realhomes-elementor-addon' );
	}

	public function get_icon() {
		// More classes for icons can be found at https://pojome.github.io/elementor-icons/
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'ultra-real-homes' ];
	}

	protected function register_controls() {

		$grid_size_array = wp_get_additional_image_sizes();

		$prop_grid_size_array = array();
		foreach ( $grid_size_array as $key => $value ) {
			$str_rpl_key = ucwords( str_replace( "-", " ", $key ) );

			$prop_grid_size_array[ $key ] = $str_rpl_key . ' - ' . $value['width'] . 'x' . $value['height'];
		}

		unset( $prop_grid_size_array['partners-logo'] );
		unset( $prop_grid_size_array['property-detail-slider-thumb'] );
		unset( $prop_grid_size_array['post-thumbnail'] );
		unset( $prop_grid_size_array['agent-image'] );
		unset( $prop_grid_size_array['gallery-two-column-image'] );
		unset( $prop_grid_size_array['post-featured-image'] );

		$default_prop_grid_size = 'property-thumb-image';

		$allowed_html = array(
			'a'      => array(
				'href'  => array(),
				'title' => array()
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
		);

		$this->start_controls_section(
			'ere_properties_section',
			[
				'label' => esc_html__( 'Properties', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'carousel' => esc_html__( 'Carousel', 'realhomes-elementor-addon' ),
					'grid'     => esc_html__( 'Grid', 'realhomes-elementor-addon' ),
				],
				'default' => 'carousel',
			]
		);

		$this->add_control(
			'card',
			[
				'label'   => esc_html__( 'Card Style', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => esc_html__( 'One', 'realhomes-elementor-addon' ),
					'2' => esc_html__( 'Two', 'realhomes-elementor-addon' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'ere_property_grid_thumb_sizes',
			[
				'label'   => esc_html__( 'Thumbnail Size', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => $default_prop_grid_size,
				'options' => $prop_grid_size_array
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Number of Properties', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 60,
				'step'    => 1,
				'default' => 6,
			]
		);

		// Select controls for Custom Taxonomies related to Property
		$property_taxonomies = get_object_taxonomies( 'property', 'objects' );
		if ( ! empty( $property_taxonomies ) && ! is_wp_error( $property_taxonomies ) ) {
			foreach ( $property_taxonomies as $single_tax ) {
				$options = [];
				$terms   = get_terms( $single_tax->name );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$options[ $term->slug ] = $term->name;
					}
				}

				$this->add_control(
					$single_tax->name,
					[
						'label'       => $single_tax->label,
						'type'        => \Elementor\Controls_Manager::SELECT2,
						'multiple'    => true,
						'label_block' => true,
						'options'     => $options,
					]
				);
			}
		}

		// Sorting Controls
		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'date'       => esc_html__( 'Date', 'realhomes-elementor-addon' ),
					'price'      => esc_html__( 'Price', 'realhomes-elementor-addon' ),
					'title'      => esc_html__( 'Title', 'realhomes-elementor-addon' ),
					'menu_order' => esc_html__( 'Menu Order', 'realhomes-elementor-addon' ),
					'rand'       => esc_html__( 'Random', 'realhomes-elementor-addon' ),
				],
				'default' => 'date',
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'asc'  => esc_html__( 'Ascending', 'realhomes-elementor-addon' ),
					'desc' => esc_html__( 'Descending', 'realhomes-elementor-addon' ),
				],
				'default' => 'desc',
			]
		);

		$this->add_control(
			'show_only_featured',
			[
				'label'        => esc_html__( 'Show Only Featured Properties', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'skip_sticky_properties',
			[
				'label'        => esc_html__( 'Skip Sticky Properties', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'offset',
			[
				'label'   => esc_html__( 'Offset or Skip From Start', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => '0',
			]
		);


		$this->add_control(
			'ere_show_property_status',
			[
				'label'        => esc_html__( 'Show Property Status', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'ere_show_featured_tag',
			[
				'label'        => esc_html__( 'Show Featured Tag', 'realhomes-elementor-addon' ),
				'description'  => wp_kses( __( 'Show if property is set to featured', 'realhomes-elementor-addon' ), $allowed_html ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'ere_show_label_tags',
			[
				'label'        => esc_html__( 'Show Property Label Tag', 'realhomes-elementor-addon' ),
				'description'  => wp_kses( __( 'Show if property label text is set', 'realhomes-elementor-addon' ), $allowed_html ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'ere_show_property_media_count',
			[
				'label'        => esc_html__( 'Show Property Media Count', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'card' => '1',
				],
			]
		);


		$this->add_control(
			'ere_enable_fav_properties',
			[
				'label'        => esc_html__( 'Show Add To Favourite Button', 'realhomes-elementor-addon' ),
				'description'  => wp_kses( __( '<strong>Important:</strong> Make sure to select <strong>Show</strong> in Customizer <strong>Favorites</strong> settings. ', 'realhomes-elementor-addon' ), $allowed_html ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'ere_enable_compare_properties',
			[
				'label'        => esc_html__( 'Show Add To Compare Button  ', 'realhomes-elementor-addon' ),
				'description'  => wp_kses( __( '<strong>Important:</strong> Make sure <strong>Compare Properties</strong> is <strong>enabled</strong> in Customizer settings. ', 'realhomes-elementor-addon' ), $allowed_html ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'show_address',
			[
				'label'        => esc_html__( 'Show Address', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'ere_show_property_type',
			[
				'label'        => esc_html__( 'Show Property Type', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label'        => esc_html__( 'Show Pagination', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout' => 'grid',
				],
			]
		);

		if ( $this->is_rvr_enabled ) {
			$this->add_control(
				'rhea_rating_enable',
				[
					'label'        => esc_html__( 'Show Ratings?', 'realhomes-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
					'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => [
						'card' => '1',
					],
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'ere_properties_meta_settings',
			[
				'label' => esc_html__( 'Meta Settings', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$get_meta = array(
			'bedrooms'   => esc_html__( 'Bedrooms', 'realhomes-elementor-addon' ),
			'bathrooms'  => esc_html__( 'Bathrooms', 'realhomes-elementor-addon' ),
			'area'       => esc_html__( 'Area', 'realhomes-elementor-addon' ),
			'garage'     => esc_html__( 'Garages/Parking', 'realhomes-elementor-addon' ),
			'year-built' => esc_html__( 'Year Built', 'realhomes-elementor-addon' ),
			'lot-size'   => esc_html__( 'Lot Size', 'realhomes-elementor-addon' ),
		);

		$meta_defaults = array(
			array(
				'rhea_property_meta_display' => 'bedrooms',
				'rhea_meta_repeater_label'   => esc_html__( 'Bedrooms', 'realhomes-elementor-addon' ),
			),
			array(
				'rhea_property_meta_display' => 'bathrooms',
				'rhea_meta_repeater_label'   => esc_html__( 'Bathrooms', 'realhomes-elementor-addon' ),
			),
			array(
				'rhea_property_meta_display' => 'area',
				'rhea_meta_repeater_label'   => esc_html__( 'Area', 'realhomes-elementor-addon' ),
			),
		);

		if ( $this->is_rvr_enabled ) {
			$get_meta = array(
				'bedrooms'   => esc_html__( 'Bedrooms', 'realhomes-elementor-addon' ),
				'bathrooms'  => esc_html__( 'Bathrooms', 'realhomes-elementor-addon' ),
				'area'       => esc_html__( 'Area', 'realhomes-elementor-addon' ),
				'garage'     => esc_html__( 'Garages/Parking', 'realhomes-elementor-addon' ),
				'year-built' => esc_html__( 'Year Built', 'realhomes-elementor-addon' ),
				'lot-size'   => esc_html__( 'Lot Size', 'realhomes-elementor-addon' ),
				'guests'     => esc_html__( 'Guests Capacity', 'realhomes-elementor-addon' ),
				'min-stay'   => esc_html__( 'Min Stay', 'realhomes-elementor-addon' ),
			);

			$meta_defaults = array(
				array(
					'rhea_property_meta_display' => 'bedrooms',
					'rhea_meta_repeater_label'   => esc_html__( 'Bedrooms', 'realhomes-elementor-addon' ),
				),
				array(
					'rhea_property_meta_display' => 'bathrooms',
					'rhea_meta_repeater_label'   => esc_html__( 'Bathrooms', 'realhomes-elementor-addon' ),
				),
				array(
					'rhea_property_meta_display' => 'guests',
					'rhea_meta_repeater_label'   => esc_html__( 'Guests', 'realhomes-elementor-addon' ),
				),
				array(
					'rhea_property_meta_display' => 'area',
					'rhea_meta_repeater_label'   => esc_html__( 'Area', 'realhomes-elementor-addon' ),
				),
			);
		}

		$meta_repeater = new \Elementor\Repeater();

		$meta_repeater->add_control(
			'rhea_property_meta_display',
			[
				'label'   => esc_html__( 'Select Meta', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $get_meta,
			]
		);

		$meta_repeater->add_control(
			'rhea_meta_repeater_label',
			[
				'label'   => esc_html__( 'Meta Label', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Add Label', 'realhomes-elementor-addon' ),
			]
		);

		$this->add_control(
			'rhea_add_meta_select',
			[
				'label'       => esc_html__( 'Add Meta', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $meta_repeater->get_controls(),
				'default'     => $meta_defaults,
				'title_field' => ' {{{ rhea_meta_repeater_label }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ere_property_typo_section',
			[
				'label' => esc_html__( 'Typography', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'property_top_tags_typography',
				'label'     => esc_html__( 'Top Tags', 'realhomes-elementor-addon' ),
				'scheme'    => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .rhea-ultra-status-box span,{{WRAPPER}} .rhea-ultra-status-box a',
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'property_heading_typography',
				'label'    => esc_html__( 'Heading', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} h3.rhea-ultra-property-title a, {{WRAPPER}} .rhea-ultra-property-card-two-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'property_address_typography',
				'label'    => esc_html__( 'Address', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rhea_address_ultra a, {{WRAPPER}} .rhea-ultra-property-card-two-address',
			]
		);

		$this->add_responsive_control(
			'address_icon_size',
			[
				'label'           => esc_html__( 'Address Marker icon size', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 40,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rhea_address_ultra .rhea_ultra_address_pin' => 'width: {{SIZE}}{{UNIT}};',

				],
				'condition'       => [
					'card' => '1',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'property_types_typography',
				'label'     => esc_html__( 'Property Types', 'realhomes-elementor-addon' ),
				'scheme'    => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .rhea-ultra-property-types small',
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'property_price_prefix_typography',
				'label'     => esc_html__( 'Price Prefix (i.e From)', 'realhomes-elementor-addon' ),
				'scheme'    => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} p.rh_prop_card__price_ultra .ere-price-prefix',
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'property_price_typography',
				'label'    => esc_html__( 'Price', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} p.rh_prop_card__price_ultra .ere-price-display, {{WRAPPER}} p.rh_prop_card__price_ultra .property-current-price, {{WRAPPER}} .rhea-ultra-property-card-two-price, {{WRAPPER}} .rhea-ultra-property-card-two .ere-price-display',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'property_old_price_typography',
				'label'     => esc_html__( 'Old Price', 'realhomes-elementor-addon' ),
				'scheme'    => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} p.rh_prop_card__price_ultra .property-old-price',
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'property_price_postfix_typography',
				'label'     => esc_html__( 'Price Postfix (i.e Monthly)', 'realhomes-elementor-addon' ),
				'scheme'    => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} p.rh_prop_card__price_ultra .ere-price-postfix, {{WRAPPER}} p.rh_prop_card__price_ultra .ere-price-slash',
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'show_price_slash',
			[
				'label'        => esc_html__( 'Show Price Postfix Separator (i.e "/")', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'card' => '1',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'property_meta_labels_typography',
				'label'    => esc_html__( 'Meta Labels', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rhea_ultra_meta_box .figure',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'property_meta_figures_typography',
				'label'    => esc_html__( 'Figures postfix (i.e sqft)', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rhea_ultra_meta_box .label',
			]
		);

		$this->add_responsive_control(
			'meta_icon_icon_size',
			[
				'label'           => esc_html__( 'Meta icon size', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 40,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rh_prop_card_meta_wrap_ultra svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'card' => '1',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'link_button_typography',
				'label'     => esc_html__( 'Button', 'realhomes-elementor-addon' ),
				'scheme'    => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .rhea-ultra-property-card-two-link',
				'condition' => [
					'card' => '2',
				],
			]
		);

		if ( $this->is_rvr_enabled ) {
			$this->add_responsive_control(
				'rating_stars_size',
				[
					'label'           => esc_html__( 'Rating star size', 'realhomes-elementor-addon' ),
					'type'            => \Elementor\Controls_Manager::SLIDER,
					'range'           => [
						'px' => [
							'min' => 0,
							'max' => 40,
						],
					],
					'desktop_default' => [
						'size' => '',
						'unit' => 'px',
					],
					'tablet_default'  => [
						'size' => '',
						'unit' => 'px',
					],
					'mobile_default'  => [
						'size' => '',
						'unit' => 'px',
					],
					'selectors'       => [
						'{{WRAPPER}} .rvr_card_info_wrap .rh-ultra-rvr-rating .rating-stars i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name'     => 'property_added_date_typography',
					'label'    => esc_html__( 'Added date typography', 'realhomes-elementor-addon' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .rvr_card_info_wrap .added-date',
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'ere_properties_labels',
			[
				'label'     => esc_html__( 'Property Labels', 'realhomes-elementor-addon' ),
				'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'ere_property_featured_label',
			[
				'label'   => esc_html__( 'Featured', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Featured', 'realhomes-elementor-addon' ),
			]
		);

		$this->add_control(
			'ere_property_fav_label',
			[
				'label'   => esc_html__( 'Add To Favourite', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Add To Favourite', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'ere_property_fav_added_label',
			[
				'label'   => esc_html__( 'Added To Favourite', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Added To Favourite', 'realhomes-elementor-addon' ),
			]
		);

		$this->add_control(
			'ere_property_compare_label',
			[
				'label'   => esc_html__( 'Add To Compare', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Add To Compare', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'ere_property_compare_added_label',
			[
				'label'   => esc_html__( 'Added To Compare', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Added To Compare', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'ere_property_rvt_date_added_label',
			[
				'label'   => esc_html__( 'Date Added Label', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Added:', 'realhomes-elementor-addon' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ere_property_basic_styles',
			[
				'label' => esc_html__( 'Basic Styles', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'property_card_border_radius',
			[
				'label'      => esc_html__( 'Property Card Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-property-card-two' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'property_card_padding',
			[
				'label'      => esc_html__( 'Property Card Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-property-card-two' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'featured_border_radius',
			[
				'label'      => esc_html__( 'Featured Image Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-property-card-two-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'property_card_content_padding',
			[
				'label'      => esc_html__( 'Property Card Content Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-property-card-two-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_border_radius',
			[
				'label'      => esc_html__( 'Thumbnail Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-property-thumb a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'top_tag_border_radius',
			[
				'label'      => esc_html__( 'Top Tags Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-status-box span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rhea-ultra-status-box a'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'media_count_border_radius',
			[
				'label'      => esc_html__( 'Media Count Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea_ultra_media_count .rhea_media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'thumbnail_margin_bottom',
			[
				'label'           => esc_html__( 'Thumbnail Margin bottom', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rhea-ultra-card-thumb-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'card' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin_bottom',
			[
				'label'           => esc_html__( 'Title Margin bottom', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rhea-ultra-property-title, {{WRAPPER}} .rhea-ultra-property-card-two-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',

				],
			]
		);


		$this->add_responsive_control(
			'address_margin_bottom',
			[
				'label'           => esc_html__( 'Address Margin bottom', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rhea_address_ultra, {{WRAPPER}} .rhea-ultra-property-card-two-address' => 'margin-bottom: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'meta_margin_bottom',
			[
				'label'     => esc_html__( 'Meta Margin bottom', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-property-card-two .rh_prop_card_meta_wrap_ultra' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'card' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Button Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-property-card-two-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Button Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-property-card-two-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'card' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'type_margin_bottom',
			[
				'label'           => esc_html__( 'Property Type Margin bottom', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rhea-ultra-property-types' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'card' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'price_column_gap',
			[
				'label'           => esc_html__( 'Column gap price/meta', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rhea-ultra-price-meta-box' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'card' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'mta_column_gap',
			[
				'label'           => esc_html__( 'Column gap meta items', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rh_prop_card_meta_wrap_ultra' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'card' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'mta_row_gap',
			[
				'label'           => esc_html__( 'Row gap meta items', 'realhomes-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .rh_prop_card_meta_wrap_ultra' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'       => [
					'card' => '1',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'top_tags_color',
			[
				'label'     => esc_html__( 'Top Tags Colors', 'realhomes-elementor-addon' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_property_status_background',
			[
				'label'     => esc_html__( 'Status Tag Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea-ultra-status' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_status_background_hover',
			[
				'label'     => esc_html__( 'Status Tag Background Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea-ultra-status:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_status_border_color',
			[
				'label'     => esc_html__( 'Status Tag Border', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea-ultra-status' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_status_border_hover_color',
			[
				'label'     => esc_html__( 'Status Tag Border Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea-ultra-status:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_status_text_color',
			[
				'label'     => esc_html__( 'Status Tag Text', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea-ultra-status' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_status_text_hover_color',
			[
				'label'     => esc_html__( 'Status Tag Text Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea-ultra-status:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_featured_background',
			[
				'label'     => esc_html__( 'Featured Tag Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_featured' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_featured_background_hover',
			[
				'label'     => esc_html__( 'Featured Tag Background Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_featured:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_featured_border',
			[
				'label'     => esc_html__( 'Featured Tag Border', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_featured' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_featured_border_hover',
			[
				'label'     => esc_html__( 'Featured Tag Border Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_featured:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_featured_color',
			[
				'label'     => esc_html__( 'Featured Tag Text', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_featured' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_featured_color_hover',
			[
				'label'     => esc_html__( 'Featured Tag Text Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_featured:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_label_background',
			[
				'label'     => esc_html__( 'Label Tag Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_hot' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_label_background_hover',
			[
				'label'     => esc_html__( 'Label Tag Background Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_hot:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_label_border',
			[
				'label'     => esc_html__( 'Label Tag Border', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_hot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_label_border_hover',
			[
				'label'     => esc_html__( 'Label Tag Border Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_hot:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_label_color',
			[
				'label'     => esc_html__( 'Label Tag Text', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_hot' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_label_color_hover',
			[
				'label'     => esc_html__( 'Label Tag Text Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-status-box .rhea_ultra_hot:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_media_background',
			[
				'label'     => esc_html__( 'Media Count Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_ultra_media_count .rhea_media' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_media_background_hover',
			[
				'label'     => esc_html__( 'Media Count Background Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_ultra_media_count .rhea_media:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_media_text',
			[
				'label'     => esc_html__( 'Media Count Text', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_ultra_media_count .rhea_media span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea_ultra_media_count svg'              => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_property_media_text_hover',
			[
				'label'     => esc_html__( 'Media Count Text Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_ultra_media_count .rhea_media:hover span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea_ultra_media_count .rhea_media:hover svg'  => 'fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ultra_property_colors',
			[
				'label' => esc_html__( 'Colors', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'property_card_bg',
			[
				'label'     => esc_html__( 'Property Card Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-property-card-two' => 'background: {{VALUE}}',
				],
				'condition' => [
					'card' => '2',
				],
			]
		);

		$this->add_control(
			'rhea_tooltip-bg-color',
			[
				'label'     => esc_html__( 'ToolTip Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-tooltip [data-tooltip]::before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-tooltip [data-tooltip]::after'  => 'background: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_tooltip-text-color',
			[
				'label'     => esc_html__( 'ToolTip Text', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-tooltip [data-tooltip]::after' => 'color: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_favourite_compare_bg',
			[
				'label'     => esc_html__( 'Favourite/Compare Button Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .favorite-btn-wrap a'     => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-bottom-box .favorite-btn-wrap span'  => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-bottom-box .rhea_compare_icons a'    => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-bottom-box .rhea_compare_icons span' => 'background: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_favourite_compare_icon_dark',
			[
				'label'     => esc_html__( 'Favourite/Compare Button Icon Outline', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .favorite-btn-wrap a .rh-ultra-dark'  => 'fill: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-bottom-box .rhea_compare_icons a .rh-ultra-dark' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_favourite_compare_icon_light',
			[
				'label'     => esc_html__( 'Favourite/Compare Button Inner', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .favorite-btn-wrap a .rh-ultra-light'  => 'fill: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-bottom-box .rhea_compare_icons a .rh-ultra-light' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_favourite_added_bg',
			[
				'label'     => esc_html__( 'Favourite Added Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .favorite-btn-wrap span' => 'background: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);
		$this->add_control(
			'rhea_favourite_added_icon_dark',
			[
				'label'     => esc_html__( 'Favourite Added Icon Outline', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .favorite-btn-wrap span .rh-ultra-dark' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);
		$this->add_control(
			'rhea_favourite_added_icon_light',
			[
				'label'     => esc_html__( 'Favourite Added Icon Inner', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .favorite-btn-wrap span .rh-ultra-light' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_compare_added_bg',
			[
				'label'     => esc_html__( 'Compare Added Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .rhea_compare_icons span' => 'background: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_compare_added_icon_dark',
			[
				'label'     => esc_html__( 'Compare Added Icon Outline', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .rhea_compare_icons span .rh-ultra-dark' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_compare_added_icon_light',
			[
				'label'     => esc_html__( 'Compare Added Icon Inner', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-bottom-box .rhea_compare_icons span .rh-ultra-light' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);
		$this->add_control(
			'rhea_title_color',
			[
				'label'     => esc_html__( 'Property Title', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h3.rhea-ultra-property-title a, {{WRAPPER}} .rhea-ultra-property-card-two-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_title_color_hover',
			[
				'label'     => esc_html__( 'Property Title Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h3.rhea-ultra-property-title a:hover, {{WRAPPER}} .rhea-ultra-property-card-two-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'rhea_address_icon_color',
			[
				'label'     => esc_html__( 'Address Icon', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_address_ultra .rhea_ultra_address_pin svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_address_icon_color_hover',
			[
				'label'     => esc_html__( 'Address Icon Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_address_ultra a:hover .rhea_ultra_address_pin svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_address_text_color',
			[
				'label'     => esc_html__( 'Address Text', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_address_ultra a, {{WRAPPER}} .rhea-ultra-property-card-two-address' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_address_text_color_hover',
			[
				'label'     => esc_html__( 'Address Text Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_address_ultra a:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);

		$this->add_control(
			'rhea_status_text_color',
			[
				'label'     => esc_html__( 'Type', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-card-detail-wrapper .rhea-ultra-property-types small' => 'color: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);
		$this->add_control(
			'rhea_price_text_color',
			[
				'label'     => esc_html__( 'Price', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.rh_prop_card__price_ultra .property-current-price, {{WRAPPER}} .rhea-ultra-property-card-two-price'         => 'color: {{VALUE}}',
					'{{WRAPPER}} p.rh_prop_card__price_ultra .ere-price-display, {{WRAPPER}} .rhea-ultra-property-card-two .ere-price-display' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'rhea_price_prefix_color',
			[
				'label'     => esc_html__( 'Price Prefix (i.e From)', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.rh_prop_card__price_ultra .ere-price-prefix' => 'color: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);
		$this->add_control(
			'rhea_price_postfix_color',
			[
				'label'     => esc_html__( 'Price Postfix (i.e Monthly)', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.rh_prop_card__price_ultra .ere-price-postfix' => 'color: {{VALUE}}',
					'{{WRAPPER}} p.rh_prop_card__price_ultra .ere-price-slash'   => 'color: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);
		$this->add_control(
			'rhea_old_price_color',
			[
				'label'     => esc_html__( 'Old Price', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.rh_prop_card__price_ultra .property-old-price' => 'color: {{VALUE}}',
				],
				'condition' => [
					'card' => '1',
				],
			]
		);
		$this->add_control(
			'rhea_meta_icon_color',
			[
				'label'     => esc_html__( 'Meta Icons', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ultra-meta' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'rhea_meta_figure_color',
			[
				'label'     => esc_html__( 'Meta Figures', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_ultra_meta_box .figure' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'rhea_meta_figure_postfix_color',
			[
				'label'     => esc_html__( 'Meta Figures postfix (i.e sqft)', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea_ultra_meta_box .label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Button Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-property-card-two-link' => 'border-color: {{VALUE}}; color: {{VALUE}}',
				],
				'condition' => [
					'card' => '2',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Button Hover Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-property-card-two-link:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'card' => '2',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label'     => esc_html__( 'Button Hover Background Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-property-card-two-link:hover' => 'border-color: {{VALUE}}; background-color: {{VALUE}}',
				],
				'condition' => [
					'card' => '2',
				],
			]
		);

		if ( $this->is_rvr_enabled ) {
			$this->add_control(
				'rhea_rating_stars_color',
				[
					'label'     => esc_html__( 'Rating Stars Color', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .rvr_card_info_wrap .rh-ultra-rvr-rating .rating-stars i' => 'color: {{VALUE}}',
					],
					'condition' => [
						'card' => '1',
					],
				]
			);
			$this->add_control(
				'rhea_added_date_label_color',
				[
					'label'     => esc_html__( 'Added Date Label Color', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .rvr_card_info_wrap .added-date .added-title' => 'color: {{VALUE}}',
					],
					'condition' => [
						'card' => '1',
					],
				]
			);
			$this->add_control(
				'rhea_added_date_color',
				[
					'label'     => esc_html__( 'Added Date Color', 'realhomes-elementor-addon' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .rvr_card_info_wrap .added-date' => 'color: {{VALUE}}',
					],
					'condition' => [
						'card' => '1',
					],
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'rhea_pagination',
			[
				'label'     => esc_html__( 'Pagination', 'realhomes-elementor-addon' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout'          => 'grid',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'ajax_pagination',
			[
				'label'        => esc_html__( 'Ajax Pagination', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_responsive_control(
			'pagination_margin',
			[
				'label'      => esc_html__( 'Container Margin', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label'      => esc_html__( 'Container Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_items_padding',
			[
				'label'      => esc_html__( 'Items Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_items_spacing',
			[
				'label'     => esc_html__( 'Items Spacing', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_items_border_radius',
			[
				'label'      => esc_html__( 'Items Border Radius', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_items_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_items_text_hover_color',
			[
				'label'     => esc_html__( 'Text Hover Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination a:hover, {{WRAPPER}} .rhea-ultra-properties-pagination .pagination a.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_items_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination a' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_items_bg_hover_color',
			[
				'label'     => esc_html__( 'Background Hover Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-properties-pagination .pagination a:hover, {{WRAPPER}} .rhea-ultra-properties-pagination .pagination a.current' => 'background: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'pagination_items_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-ultra-properties-pagination .pagination a',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_items_typography',
				'label'    => esc_html__( 'Typography', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rhea-ultra-properties-pagination .pagination a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_nav_styles',
			[
				'label'     => esc_html__( 'Slider Navigations', 'realhomes-elementor-addon' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_margin_top',
			[
				'label'     => esc_html__( 'Slider nav control margin top', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box' => 'margin-top: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_column_gap',
			[
				'label'     => esc_html__( 'Slider Nav Controls Gap', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box' => 'column-gap: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'slider_control_nav_margin',
			[
				'label'     => esc_html__( 'Slider nav controls margin', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .rhea-ultra-owl-dots button' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',

				],
			]
		);

		$this->add_control(
			'rhea_slider_directional_nav_color',
			[
				'label'     => esc_html__( 'Directional Nav Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-prev' => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-next' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_slider_directional_icon_color',
			[
				'label'     => esc_html__( 'Directional Nav icon ', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-next i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_slider_directional_nav_hover_color',
			[
				'label'     => esc_html__( 'Directional Nav Background Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-prev:hover' => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-next:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_slider_directional_icon_hover_color',
			[
				'label'     => esc_html__( 'Directional Nav icon hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-prev:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-next:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_slider_directional_nav_disable_color',
			[
				'label'     => esc_html__( 'Directional Nav Background Disabled', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-prev.disabled' => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-next.disabled' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_slider_directional_icon_disabled_color',
			[
				'label'     => esc_html__( 'Directional Nav icon Disabled', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-prev.disabled i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .owl-next.disabled i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_slider_control_nav_background',
			[
				'label'     => esc_html__( 'Slider Control Nav Background Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .rhea-ultra-owl-dots' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow_control_nav',
				'label'    => esc_html__( 'Control Nav Box Shadow', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-ultra-nav-box .rhea-ultra-owl-dots',
			]
		);

		$this->add_control(
			'rhea_slider_control_nav',
			[
				'label'     => esc_html__( 'Slider Control Nav Dots Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .rhea-ultra-owl-dots button:after' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rhea_slider_control_nav_active',
			[
				'label'     => esc_html__( 'Slider Control Nav Active/hover Dots Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-ultra-nav-box .rhea-ultra-owl-dots button.active'       => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .rhea-ultra-owl-dots button.active:after' => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .rhea-ultra-owl-dots button:hover'        => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-ultra-nav-box .rhea-ultra-owl-dots button:hover:after'  => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		global $settings, $widget_id, $properties_query;

		$settings  = $this->get_settings_for_display();
		$widget_id = $this->get_id();

		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) { // if is static front page
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		if ( $settings['offset'] ) {
			$offset = $settings['offset'] + ( $paged - 1 ) * $settings['posts_per_page'];
		} else {
			$offset = '';
		}

		$properties_args = array(
			'post_type'      => 'property',
			'posts_per_page' => $settings['posts_per_page'],
			'order'          => $settings['order'],
			'offset'         => $offset,
			'post_status'    => 'publish',
			'paged'          => $paged,
		);

		if ( $settings['skip_sticky_properties'] !== 'yes' ) {
			$properties_args['meta_key'] = 'REAL_HOMES_sticky';
		}

		// Sorting
		if ( 'price' === $settings['orderby'] ) {
			$properties_args['orderby']  = 'meta_value_num';
			$properties_args['meta_key'] = 'REAL_HOMES_property_price';
		} else {
			// for date, title, menu_order and rand
			$properties_args['orderby'] = $settings['orderby'];
		}

		// Filter based on custom taxonomies
		$property_taxonomies = get_object_taxonomies( 'property', 'objects' );
		if ( ! empty( $property_taxonomies ) && ! is_wp_error( $property_taxonomies ) ) {
			foreach ( $property_taxonomies as $single_tax ) {
				$setting_key = $single_tax->name;
				if ( ! empty( $settings[ $setting_key ] ) ) {
					$properties_args['tax_query'][] = [
						'taxonomy' => $setting_key,
						'field'    => 'slug',
						'terms'    => $settings[ $setting_key ],
					];
				}
			}

			if ( isset( $properties_args['tax_query'] ) && count( $properties_args['tax_query'] ) > 1 ) {
				$properties_args['tax_query']['relation'] = 'AND';
			}
		}

		$meta_query = array();
		if ( 'yes' === $settings['show_only_featured'] ) {
			$meta_query[] = array(
				'key'     => 'REAL_HOMES_featured',
				'value'   => 1,
				'compare' => '=',
				'type'    => 'NUMERIC',
			);

			$properties_args['meta_query'] = $meta_query;
		}

		$properties_query = new WP_Query( apply_filters( 'rhea_modern_properties_widget', $properties_args ) );

		if ( $properties_query->have_posts() ) {
			if ( 'carousel' === $settings['layout'] ) {
				rhea_get_template_part( 'elementor/widgets/properties-widget/carousel' );
			} else {
				rhea_get_template_part( 'elementor/widgets/properties-widget/grid' );
			}
		}
	}
}
