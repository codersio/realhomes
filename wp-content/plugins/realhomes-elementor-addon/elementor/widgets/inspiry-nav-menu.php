<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Widget for Available RealHomes Nav Menus .
 *
 * @since 0.9.7
 */
class RHEA_Nav_Menu_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'rhea-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'RealHomes Nav Menu', 'realhomes-elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'real-homes' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'menu-settings-section',
			[
				'label' => esc_html__( 'Settings', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Get Array of Available Menus
		$menu_list = rhea_get_available_menus();

		if ( ! empty( $menu_list ) ) {
			$this->add_control(
				'select-menu',
				[
					'label'        => esc_html__( 'Select Menu', 'realhomes-elementor-addon' ),
					'type'         => \Elementor\Controls_Manager::SELECT,
					'default'      => array_keys( $menu_list )[0],
					'options'      => $menu_list,
					'save_default' => true,
					'description'  => sprintf( __( 'You can create/manage your Menus from <a href="%s" target="_blank">Menus screen</a>.', 'realhomes-elementor-addon' ), admin_url( 'nav-menus.php' ) ),
					'separator'    => 'after',

				]
			);
		} else {
			$this->add_control(
				'no-menu',
				[
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . esc_html__( 'No menu available.', 'realhomes-elementor-addon' ) . '</strong><br>' . sprintf( __( 'You can create/manage your Menus from <a href="%s" target="_blank">Menus screen</a>.', 'realhomes-elementor-addon' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}
		$this->add_control(
			'main-menu-head',
			[
				'label' => esc_html__( 'Desktop Menu', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'nav-layout',
			[
				'label'              => esc_html__( 'Menu Layout', 'realhomes-elementor-addon' ),
				'type'               => \Elementor\Controls_Manager::SELECT,
				'default'            => 'horizontal',
				'frontend_available' => true,
				'options'            => [
					'horizontal' => esc_html__( 'Horizontal', 'realhomes-elementor-addon' ),
					'vertical'   => esc_html__( 'Vertical', 'realhomes-elementor-addon' ),
				],
			]
		);

		$this->add_control(
			'animation-styles',
			[
				'label'              => esc_html__( 'Top Menu Style', 'realhomes-elementor-addon' ),
				'type'               => \Elementor\Controls_Manager::SELECT,
				'default'            => 'horizontal',
				'frontend_available' => true,
				'options'            => [
					'default'         => esc_html__( 'Default', 'realhomes-elementor-addon' ),
					'slide-in-left'   => esc_html__( 'Slide In Left', 'realhomes-elementor-addon' ),
					'slide-in-right'  => esc_html__( 'Slide In Right', 'realhomes-elementor-addon' ),
					'slide-in-top'    => esc_html__( 'Slide In Top', 'realhomes-elementor-addon' ),
					'slide-in-bottom' => esc_html__( 'Slide In Bottom', 'realhomes-elementor-addon' ),
				],
			]
		);


		$this->add_control(
			'menu-item-icon',
			[
				'label'   => esc_html__( 'Drop Down Icon', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'  => esc_html__( 'None', 'realhomes-elementor-addon' ),
					'caret' => esc_html__( 'Caret', 'realhomes-elementor-addon' ),
					'angle' => esc_html__( 'Angle', 'realhomes-elementor-addon' ),
				],

			]
		);

		$this->add_control(
			'menu-align-items',
			[
				'label'   => esc_html__( 'Menu Align', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-right',
					],
				],

				'selectors_dictionary' => [
					'left'   => 'justify-content: flex-start',
					'center' => 'justify-content: center',
					'right'  => 'justify-content: flex-end',
				],
				'selectors'            => [
					'{{WRAPPER}} .rhea-elementor-nav-menu' => '{{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'main-menu',
				'label'    => esc_html__( 'Main Menu', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' =>
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li > a',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'main-menu-dropdown',
				'label'    => esc_html__( 'Main Menu Dropdown', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' =>
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li li a',
			]
		);

		$this->add_responsive_control(
			'dropdown-icons-size',
			[
				'label'     => esc_html__( 'Dropdown Icon Size', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper .rhea-menu-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_menu_item_description',
			[
				'label'        => esc_html__( 'Show Menu Item Description', 'realhomes-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'realhomes-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'realhomes-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'mobile-menu-head',
			[
				'label'     => esc_html__( 'Mobile Menu', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'menu-trigger-icon',
			[
				'label'       => esc_html__( 'Hamburger Menu Icon', 'realhomes-elementor-addon' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'label_block' => true
			]
		);


		$this->add_control(
			'mobile_menu_show',
			[
				'label'   => esc_html__( 'Mobile Menu Show', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'   => esc_html__( 'None', 'realhomes-elementor-addon' ),
					'tablet' => esc_html__( 'Tablet and less', 'realhomes-elementor-addon' ),
					'mobile' => esc_html__( 'Mobile and less ', 'realhomes-elementor-addon' ),
				],
			]
		);

		$this->add_control(
			'ham-menu-align-items',
			[
				'label'   => esc_html__( 'Hamburger Menu Align', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'realhomes-elementor-addon' ),
						'icon'  => 'eicon-h-align-right',
					],
				],

				'selectors_dictionary' => [
					'left'   => 'justify-content: flex-start',
					'center' => 'justify-content: center',
					'right'  => 'justify-content: flex-end',
				],
				'selectors'            => [
					'{{WRAPPER}} .rhea-menu-bars-wrapper' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile-menu-item-icon',
			[
				'label'   => esc_html__( 'Drop Down Icon', 'realhomes-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'  => esc_html__( 'None', 'realhomes-elementor-addon' ),
					'caret' => esc_html__( 'Caret', 'realhomes-elementor-addon' ),
					'angle' => esc_html__( 'Angle', 'realhomes-elementor-addon' ),
				],

			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'mobile-main-menu-dropdown',
				'label'    => esc_html__( 'Mobile Menu', 'realhomes-elementor-addon' ),
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' =>
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li a',
			]
		);

		$this->add_responsive_control(
			'mobile-drop-down-icon-size',
			[
				'label'     => esc_html__( 'Mobile Dropdown Icon Size', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner .rhea-menu-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mobile-menu-hamburger-size',
			[
				'label'     => esc_html__( 'Hamburger Icon Size', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-menu-bars i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mobile-menu-close',
			[
				'label'     => esc_html__( 'Close Icon Size', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-fa-close-nav' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'menu_size_spaces_section',
			[
				'label' => esc_html__( 'Size & Spaces', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'main-menu-size-head',
			[
				'label' => esc_html__( 'Desktop Menu', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);


		$this->add_responsive_control(
			'menu-wrapper-padding',
			[
				'label'      => esc_html__( 'Menu Wrapper Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'first-level-list-item-margin',
			[
				'label'      => esc_html__( 'Menu List Item Margin', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'first-level-item-margin',
			[
				'label'      => esc_html__( 'Menu Item Margin', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'first-level-item-padding',
			[
				'label'      => esc_html__( 'Menu Item Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown-level-item-padding',
			[
				'label'      => esc_html__( 'Dropdown Item Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rhea-items-gap',
			[
				'label'     => esc_html__( 'Gap Between Items', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rhea-icon-lg-gap',
			[
				'label'     => esc_html__( 'Dropdown Icon Gap', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li a' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown-min-width',
			[
				'label'     => esc_html__( 'Dropdown Min Width', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown-hover-translate',
			[
				'label'     => esc_html__( 'Dropdown Translate On Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li:hover>ul' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);


		$this->add_responsive_control(
			'dropdown-level-ul-padding',
			[
				'label'      => esc_html__( 'Dropdown Wrapper Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'mobile-menu-size-head',
			[
				'label'     => esc_html__( 'Mobile Menu', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'mobile-menu-items-padding',
			[
				'label'      => esc_html__( 'Items Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'mobile-menu-icons-padding',
			[
				'label'      => esc_html__( 'Menu Icons Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'mobile-menu-bars-padding',
			[
				'label'      => esc_html__( 'Menu Button Padding', 'realhomes-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rhea-menu-bars' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'side-dropdown-max-width',
			[
				'label'     => esc_html__( 'Mobile Menu Sidebar Size ', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'menu_colors_section',
			[
				'label' => esc_html__( 'Colors', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'desktop-menu-color-head',
			[
				'label' => esc_html__( 'Desktop Menu', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'menu-wrapper-bg-color',
			[
				'label'     => esc_html__( 'Menu Wrapper Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-li-bg-color',
			[
				'label'     => esc_html__( 'Top Menu Items Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper .default > li > a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper .animate > li > a' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-li-text-color',
			[
				'label'     => esc_html__( 'Top Menu Items Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li > a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-li-bg-hover-color',
			[
				'label'     => esc_html__( 'Top Menu Items Hover/Current Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper .animate > li > a:before'                => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper .default > li:hover > a'                 => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper .default > li.current-menu-item > a'     => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper .default > li.current-menu-ancestor > a' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-li-text-hover-color',
			[
				'label'     => esc_html__( 'Top Menu Items Hover/Current Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li:hover > a'                 => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li.current-menu-item > a'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li.current-menu-ancestor > a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-dropdown-bg',
			[
				'label'     => esc_html__( 'Dropdown Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li ul' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-dropdown-li-bg',
			[
				'label'     => esc_html__( 'Dropdown Item Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul a' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-dropdown-li-text',
			[
				'label'     => esc_html__( 'Dropdown Item Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-dropdown-li-hover-bg',
			[
				'label'     => esc_html__( 'Dropdown hover/current Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul li:hover > a'                 => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul li.current-menu-item > a'     => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul li.current-menu-ancestor > a' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'nav-menu-dropdown-li-hover-text',
			[
				'label'     => esc_html__( 'Dropdown hover/current Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul li:hover > a'                 => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul li.current-menu-item > a'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul li.current-menu-ancestor > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'nav-menu-item-description-color',
			[
				'label'     => esc_html__( 'Item Description Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li a .menu-item-desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'nav-menu-item-description-background',
			[
				'label'     => esc_html__( 'Item Description Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li a .menu-item-desc' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'nav-menu-item-description-hover-color',
			[
				'label'     => esc_html__( 'Item Description Color Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li a:hover .menu-item-desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'nav-menu-item-description-hover-background',
			[
				'label'     => esc_html__( 'Item Description Background Hover', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li a:hover .menu-item-desc' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile-menu-color-head',
			[
				'label'     => esc_html__( 'Mobile Menu', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'menu-bars-bg',
			[
				'label'     => esc_html__( 'Menu Bars Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-menu-bars' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'menu-bars-bg-hover',
			[
				'label'     => esc_html__( 'Menu Bars Hover Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-menu-bars:hover' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'menu-bars-color',
			[
				'label'     => esc_html__( 'Menu Bars Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-menu-bars' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'menu-bars-color-hover',
			[
				'label'     => esc_html__( 'Menu Bars Hover Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-menu-bars:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-background',
			[
				'label'     => esc_html__( 'Menu Wrapper Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-close-color',
			[
				'label'     => esc_html__( 'Close Button Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner .rhea-fa-close-nav' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-li-bg',
			[
				'label'     => esc_html__( 'Menu Item Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li a' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-li-color',
			[
				'label'     => esc_html__( 'Menu Item Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-li-hover-bg',
			[
				'label'     => esc_html__( 'Menu Hover/Current Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li:hover > a'           => 'background: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li.current-menu-item>a' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-li-hover-color',
			[
				'label'     => esc_html__( 'Menu Hover/Current Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li:hover > a'           => 'color: {{VALUE}}',
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li.current-menu-item>a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-icon-bg',
			[
				'label'     => esc_html__( 'Dropdown Icon Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li i' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-icon-bg-hover',
			[
				'label'     => esc_html__( 'Dropdown Icon Hover Background', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li i:hover' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-icon-color',
			[
				'label'     => esc_html__( 'Dropdown Icon Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li i' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'mobile-menu-icon-color-hover',
			[
				'label'     => esc_html__( 'Dropdown Icon Hover Color', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-responsive-inner li i:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'menu_border_section',
			[
				'label' => esc_html__( 'Border', 'realhomes-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'top-main-menu-border',
			[
				'label' => esc_html__( 'Top Menu Border', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'menu-border',
				'label'    => esc_html__( 'Border', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li > a',

			]
		);
		$this->add_control(
			'top-main-menu-border-hover',
			[
				'label' => esc_html__( 'Top Menu Border Hover', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'menu-border-hover',
				'label'    => esc_html__( 'Border Hover', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li:hover>a,{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li.current-menu-item>a,{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li.current-menu-ancestor>a',
			]
		);

		$this->add_control(
			'dropdown-menu-border',
			[
				'label'     => esc_html__( 'Dropdown Menu Border', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'dropdown-menu-border',
				'label'    => esc_html__( 'Dropdown Border', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul  li > a',
			]
		);

		$this->add_control(
			'dropdown-menu-border-heading',
			[
				'label' => esc_html__( 'Dropdown Menu Border Hover', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'dropdown-menu-border-hover',
				'label'    => esc_html__( 'Dropdown Border Hover', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul li:hover>a,{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu ul li.current-menu-item>a',
			]
		);

		$this->add_responsive_control(
			'menu-wrapper-border-radius',
			[
				'label'     => esc_html__( 'Menu Wrapper Border Radius', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'menu-list-border-radius',
			[
				'label'     => esc_html__( 'Top Menu Item Border Radius', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu > li > a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'dropdown-border-radius',
			[
				'label'     => esc_html__( 'Dropdown Border Radius', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li ul' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'dropdown_menu_box_shadow',
				'label'    => esc_html__( 'Dropdown Menu Box Shadow', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-nav-menu-wrapper ul.rhea-elementor-nav-menu li .sub-menu',
			]
		);

		$this->add_control(
			'mobile-menu-border-settings',
			[
				'label'     => esc_html__( 'Menu Bar Border', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'menu-bar-border',
				'label'    => esc_html__( 'Menu Bar Border ', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-menu-bars',
			]
		);
		$this->add_control(
			'mobile-menu-border-hover-settings',
			[
				'label' => esc_html__( 'Menu Bar Border Hover', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'menu-bar-border-hover',
				'label'    => esc_html__( 'Menu Bar Border Hover', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-menu-bars:hover',
			]
		);
		$this->add_responsive_control(
			'menu-bar-border-radius',
			[
				'label'     => esc_html__( 'Menu Bars Border Radius', 'realhomes-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .rhea-menu-bars' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'mobile-menu-item-border-heading',
			[
				'label' => esc_html__( 'Mobile Menu Item Border', 'realhomes-elementor-addon' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'mobile-menu-border-item',
				'label'    => esc_html__( 'Mobile Menu Item Border', 'realhomes-elementor-addon' ),
				'selector' => '{{WRAPPER}} .rhea-nav-menu-responsive-inner li a',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Process meta icon based on the settings set in related option
	 *
	 * @since 2.1.1
	 *
	 * @param string $key
	 * @param string $default
	 *
	 * @return string
	 */
	private function process_meta_icon( $key, $default ) {
		$settings     = $this->get_settings_for_display();
		$icon_setting = $settings[ $key ];
		$icon         = $default;

		if ( ! empty( $icon_setting['value'] ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $icon_setting, [ 'aria-hidden' => 'true' ] );
			$icon = ob_get_contents();
			ob_end_clean();
		} else {
			$icon = '<i class="' . esc_attr( $default ) . '"></i>';
		}

		return $icon;
	}

	public function add_menu_description( $item_output, $item, $depth, $args ) {

		if ( ! empty( $item->description ) ) {
			$item_output = '<a href=' . $item->url . '>' . $item->post_title . '<span class="menu-item-desc">' . $item->description . '</span></a>';
		}

		return $item_output;
	}

	protected function render() {

		if ( ! rhea_get_available_menus() ) {
			return;
		}
		$settings        = $this->get_settings_for_display();
		$animation_style = ' default ';
		if ( 'default' !== $settings['animation-styles'] ) {
			$animation_style = ' animate ' . $settings['animation-styles'];
		}

		$args = [
			'echo'            => true,
			'menu'            => $settings['select-menu'],
			'menu_class'      => 'rhea-elementor-nav-menu' . $animation_style,
			'menu_id'         => 'rhea-menu-' . $this->get_id(),
			'container_class' => 'rhea-menu-container rhea-menu-' . $settings['nav-layout'],
		];

		if ( $settings['show_menu_item_description'] === 'yes' ) {
			add_filter( 'walker_nav_menu_start_el', array( $this, 'add_menu_description' ), 10, 4 );
		}

		?>
        <div class="rhea-lg-menu-<?php echo esc_attr( $this->get_id() ); ?>  rhea-nav-menu-wrapper rhea-hide-menu-<?php echo esc_attr( $settings['mobile_menu_show'] ); ?> ">
			<?php
			wp_nav_menu( $args );
			?>
        </div>

		<?php
		if ( 'none' !== $settings['mobile_menu_show'] ) {
			?>

            <div class="rhea-nav-menu-responsive rhea-show-menu-<?php echo esc_attr( $settings['mobile_menu_show'] ) ?>">
                <div class="rhea-menu-bars-wrapper">
                    <div class="rhea-menu-bars rhea-bars-<?php echo esc_attr( $this->get_id() ) ?>">
						<?php
						if ( ! empty( $settings['menu-trigger-icon'] ) ) {
							echo $this->process_meta_icon( 'menu-trigger-icon', 'fa fa-bars' );
						} else {
							?>
                            <i class="fa fa-bars"></i>
							<?php
						}
						?>
                    </div>
                </div>
                <div class="rhea-nav-menu-responsive-inner rhea-inner-<?php echo esc_attr( $this->get_id() ) ?>">
                    <i class="rhea-fa-close-nav fas fa-times"></i>
					<?php
					wp_nav_menu( $args );
					?>
                </div>
            </div>

			<?php
		}

		if ( 'caret' == $settings['menu-item-icon'] ) {
			$icon_html = '<i class="rhea-menu-icon fas fa-caret-down rh_menu__indicator"></i>';
		} else if ( 'angle' == $settings['menu-item-icon'] ) {
			$icon_html = '<i class="rhea-menu-icon fas fa-angle-down rh_menu__indicator"></i>';
		} else {
			$icon_html = '';
		}

		if ( 'caret' == $settings['mobile-menu-item-icon'] ) {
			$mobile_icon_html = '<i class="rhea-menu-icon fas fa-caret-down rh_menu__indicator"></i>';
		} else if ( 'angle' == $settings['mobile-menu-item-icon'] ) {
			$mobile_icon_html = '<i class="rhea-menu-icon fas fa-angle-down rh_menu__indicator"></i>';
		} else {
			$mobile_icon_html = '';
		}
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
            <script>
                ( function ( $ ) {
                    'use strict';
                    var sub_lg_menu_parent = $( '.rhea-lg-menu-<?php echo esc_attr( $this->get_id() )?> ul.sub-menu' )
                    .prev( 'a' );
                    sub_lg_menu_parent.append( '<?php echo $icon_html ?>' );
                    var sub_menu_parent = $( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?> ul.sub-menu' )
                    .parent();
                    sub_menu_parent.prepend( '<?php echo $mobile_icon_html ?>' );

                    $( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?> .rhea-elementor-nav-menu > li .rh_menu__indicator' )
                    .on( 'click', function ( e ) {
                        e.preventDefault();
                        $( this ).parent().children( 'ul.sub-menu' ).slideToggle();
                        $( this ).toggleClass( 'rh_menu__indicator_up' );
                    } );

                    $( '.rhea-bars-<?php echo esc_attr( $this->get_id() )?>' ).on( 'click', function () {
                        $( this ).addClass( 'rhea-nav-is-open' );
                        $( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?>' ).addClass( 'rhea-show-nav' );
                    } );

                    $( '.rhea-fa-close-nav' ).on( 'click', function () {
                        $( this )
                        .parent( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?>' )
                        .removeClass( 'rhea-show-nav' );
                    } );

                    $( document ).on( 'click', function ( e ) {
                        let container = $( ".rhea-bars-<?php echo esc_attr( $this->get_id() )?>,.rhea-bars-<?php echo esc_attr( $this->get_id() )?> i,.rhea-inner-<?php echo esc_attr( $this->get_id() )?> .rhea-elementor-nav-menu" );
                        if ( ! container.is( e.target ) && container.has( e.target ).length === 0 ) {
                            $( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?>' ).removeClass( 'rhea-show-nav' );
                        }
                    } );
                } )( jQuery );

            </script>
			<?php
		} else {
			?>
            <script>
                ( function ( $ ) {
                    'use strict';
                    $( document ).on( 'ready', function () {
                        var sub_lg_menu_parent = $( '.rhea-lg-menu-<?php echo esc_attr( $this->get_id() )?> ul.sub-menu' )
                        .prev( 'a' );
                        sub_lg_menu_parent.append( '<?php echo $icon_html ?>' );
                        var sub_menu_parent = $( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?> ul.sub-menu' )
                        .parent();
                        sub_menu_parent.prepend( '<?php echo $mobile_icon_html ?>' );
                        $( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?> .rhea-elementor-nav-menu > li .rh_menu__indicator' )
                        .on( 'click', function ( e ) {
                            e.preventDefault();
                            $( this ).parent().children( 'ul.sub-menu' ).slideToggle();
                            $( this ).toggleClass( 'rh_menu__indicator_up' );
                        } );
                        $( '.rhea-bars-<?php echo esc_attr( $this->get_id() )?>' ).on( 'click', function () {
                            $( this ).addClass( 'rhea-nav-is-open' );
                            $( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?>' ).addClass( 'rhea-show-nav' );
                        } );
                        $( '.rhea-fa-close-nav' ).on( 'click', function () {
                            $( this )
                            .parent( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?>' )
                            .removeClass( 'rhea-show-nav' );
                        } );
                        //Hide Mobile Menu after item click
                        $( '.rhea-nav-menu-responsive-inner a' ).on( 'mouseup', function () {
                            $( this ).closest( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?>' )
                            .removeClass( 'rhea-show-nav' );
                        } );

                        $( document ).on( 'click', function ( e ) {
                            let container = $( ".rhea-bars-<?php echo esc_attr( $this->get_id() )?>,.rhea-bars-<?php echo esc_attr( $this->get_id() )?> i,.rhea-inner-<?php echo esc_attr( $this->get_id() )?> .rhea-elementor-nav-menu" );
                            if ( ! container.is( e.target ) && container.has( e.target ).length === 0 ) {
                                $( '.rhea-inner-<?php echo esc_attr( $this->get_id() )?>' )
                                .removeClass( 'rhea-show-nav' );
                            }
                        } );

                    } );
                } )( jQuery );


            </script>
			<?php
		}

	}
}
