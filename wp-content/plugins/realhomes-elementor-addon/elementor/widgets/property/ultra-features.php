<?php
/**
 * Property Features Elementor widget for single property
 *
 * @since 2.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RHEA_Ultra_Single_Features extends \Elementor\Widget_Base {
	public function get_name() {
		return 'rhea-ultra-single-property-features';
	}

	public function get_title() {
		return esc_html__( 'Ultra: Single Property Features', 'realhomes-elementor-addon' );
	}

	public function get_icon() {
		// More classes for icons can be found at https://pojome.github.io/elementor-icons/
		return 'eicon-bullet-list';
	}

	public function get_categories() {
		return [ 'ultra-realhomes-single-property' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'settings_section',
			[
				'label' => esc_html__( 'Settings', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'section_title',
			[
				'label'   => esc_html__( 'Section Title', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Features', 'realhomes-elementor-addon' ),
			]
		);
		$this->add_control(
			'section_title_color',
			[
				'label'     => esc_html__( 'Section Title Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__heading' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'section_title_typography',
				'label'    => esc_html__( 'Section Title Typography', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rh_property__heading',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'features_colors_section',
			[
				'label' => esc_html__( 'Basic Styles', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'features-width',
			[
				'label'      => esc_html__( 'List Item Width', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rh_property__features li' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'features-item-margin-bottom',
			[
				'label'     => esc_html__( 'List Item Margin Bottom', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rh_property__features li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'features-wrapper-margin-bottom',
			[
				'label'     => esc_html__( 'Wrapper Margin Bottom', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rh_property__features_wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'list-color',
			[
				'label'     => esc_html__( 'List Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__features li a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'list-color-hover',
			[
				'label'     => esc_html__( 'List Color Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh_property__features li a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'list-icon-dark',
			[
				'label'     => esc_html__( 'List Icon Dark', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh-ultra-stroke-dark' => 'stroke: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'list-icon-light',
			[
				'label'     => esc_html__( 'List Icon light', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rh-ultra-stroke-dark' => 'fill: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'list-typography',
			[
				'label' => esc_html__( 'Typography', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'feature_item_typography',
				'label'    => esc_html__( 'Feature Label', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rh_property__features li a',
			]
		);
		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$post_id  = get_the_ID();

		// Sample Post id for Elementor editor only
		if ( rhea_is_preview_mode() ) {
			$post_id = rhea_get_sample_property_id();
		}

		/* Property Features */
		$features_terms = get_the_terms( $post_id, 'property-feature' );
		if ( ! empty( $features_terms ) ) {
			?>
            <div class="rh_property__features_wrap margin-bottom-40px <?php realhomes_printable_section( 'features' ); ?>">
                <h4 class="rh_property__heading"><?php echo esc_html( $settings['section_title'] ); ?></h4>
				<?php
				$rh_property_features_display = get_option( 'inspiry_property_features_display', 'link' );
				?>
                <ul class="rh_property__features arrow-bullet-list">
					<?php
					foreach ( $features_terms as $feature_term ) {
						echo '<li class="rh_property__feature" id="rh_property__feature_' . esc_attr( $feature_term->term_id ) . '">';
						inspiry_safe_include_svg( '/ultra/icons/done.svg', '/assets/' );
						if ( 'link' === $rh_property_features_display ) {
							echo '<a href="' . esc_url( get_term_link( $feature_term->slug, 'property-feature' ) ) . '">' . esc_html( $feature_term->name ) . '</a>';
						} else {
							echo esc_html( $feature_term->name );
						}
						echo '</li>';
					}
					?>
                </ul>
            </div>
			<?php
		} else {
			rhea_print_no_result_for_editor();
		}

	}
}