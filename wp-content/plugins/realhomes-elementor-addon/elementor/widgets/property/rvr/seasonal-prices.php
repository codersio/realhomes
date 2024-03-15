<?php
/**
 * Property RVR Seasonal Prices
 *
 * @since 2.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RHEA_Ultra_Single_Seasonal_Prices extends \Elementor\Widget_Base {
	public function get_name() {
		return 'rhea-ultra-pdp-seasonal-prices';
	}

	public function get_title() {
		return esc_html__( 'Ultra: Seasonal Prices', 'realhomes-elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-price-list';
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
				'default' => esc_html__( 'Seasonal Prices', 'realhomes-elementor-addon' ),
			]
		);

		$this->add_control(
			'start_date_text',
			[
				'label'   => esc_html__( 'Start Date Text', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Start Date', 'realhomes-elementor-addon' ),
			]
		);

		$this->add_control(
			'end_date_text',
			[
				'label'   => esc_html__( 'End Date Text', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'End Date', 'realhomes-elementor-addon' ),
			]
		);

		$this->add_control(
			'per_night_text',
			[
				'label'   => esc_html__( 'Per Night Text', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Per Night', 'realhomes-elementor-addon' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'typography_section',
			[
				'label' => esc_html__( 'Typography', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'section_title_typography',
				'label'    => esc_html__( 'Section Title Typography', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rvr_seasonal_prices_wrap .rh_property__heading',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'list_title_typography',
				'label'    => esc_html__( 'List Titles Typography', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rvr_seasonal_prices_wrap .rvr_seasonal_prices tr th',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'list_items_typography',
				'label'    => esc_html__( 'List Items Typography', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .rvr_seasonal_prices_wrap .rvr_seasonal_prices tr td',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'styles_section',
			[
				'label' => esc_html__( 'Basic Styles', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'section_title_color',
			[
				'label'     => esc_html__( 'Section Title Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rvr_seasonal_prices_wrap .rh_property__heading' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'list_titles_color',
			[
				'label'     => esc_html__( 'List Titles Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rvr_seasonal_prices_wrap .rvr_seasonal_prices tr th' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'list_items_color',
			[
				'label'     => esc_html__( 'List Items Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rvr_seasonal_prices_wrap .rvr_seasonal_prices tr td' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'list_item_even_color',
			[
				'label'     => esc_html__( 'List Even Items Background Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rvr_seasonal_prices_wrap .rvr_seasonal_prices tr:nth-child(even)' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'list_item_odd_color',
			[
				'label'     => esc_html__( 'List Odd Items Background Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rvr_seasonal_prices_wrap .rvr_seasonal_prices tr:nth-child(odd)' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'section-padding',
			[
				'label'      => esc_html__( 'Section Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rvr_seasonal_prices_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section-margins',
			[
				'label'      => esc_html__( 'Section Margin', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rvr_seasonal_prices_wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'list-item-padding',
			[
				'label'      => esc_html__( 'List Item Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rvr_seasonal_prices_wrap .rvr_seasonal_prices tr th, .rvr_seasonal_prices_wrap .rvr_seasonal_prices tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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

		$seasonal_prices = get_post_meta( $post_id, 'rvr_seasonal_pricing', true );

		if ( ! empty( $seasonal_prices ) ) {
			$section_heading         = $settings['section_title'] ?? esc_html__( 'Seasonal Prices', 'realhomes-elementor-addon' );
			$start_date_column_label = $settings['start_date_text'] ?? esc_html__( 'Start Date', 'realhomes-elementor-addon' );
			$end_date_column_label   = $settings['end_date_text'] ?? esc_html__( 'End Date', 'realhomes-elementor-addon' );
			$price_column_label      = $settings['per_night_text'] ?? esc_html__( 'Per Night', 'realhomes-elementor-addon' );
			?>
            <div class="rvr_seasonal_prices_wrap <?php realhomes_printable_section( 'rvr/seasonal-prices' ); ?>">
                <h4 class="rh_property__heading"><?php echo esc_html( $section_heading ); ?></h4>
                <table class="rvr_seasonal_prices">
                    <tr>
                        <th class="sp-start-date-column"><?php echo esc_html( $start_date_column_label ); ?></th>
                        <th class="sp-end-date-column"><?php echo esc_html( $end_date_column_label ); ?></th>
                        <th class="sp-price-column"><?php echo esc_html( $price_column_label ); ?></th>
                    </tr>
					<?php
					foreach ( $seasonal_prices as $season_price ) {
						if ( ! empty( $season_price['rvr_price_start_date'] ) && ! empty( $season_price['rvr_price_start_date'] ) && ! empty( $season_price['rvr_price_start_date'] ) ) {
							?>
                            <tr>
                                <td><?php echo esc_html( realhomes_apply_wp_date_format( $season_price['rvr_price_start_date'] ) ); ?></td>
                                <td><?php echo esc_html( realhomes_apply_wp_date_format( $season_price['rvr_price_end_date'] ) ); ?></td>
                                <td><?php echo esc_html( ere_format_amount( intval( $season_price['rvr_price_amount'] ) ) ); ?></td>
                            </tr>
							<?php
						}
					}
					?>
                </table>
            </div>
			<?php
		} else {
			rhea_print_no_result_for_editor();
		}

	}
}