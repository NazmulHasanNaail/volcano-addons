<?php

namespace VolcanoAddons\Widgets;

use VolcanoAddons\Volcano_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Exception;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class VolcanoAddons_Style_Countdown extends Volcano_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'volcano-countdown';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Count Down', 'volcano-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'volcano-addons eicon-countdown';
	}

	public function get_keywords() {
		return [ 'countdown', 'number', 'timer', 'time', 'date' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'volcano-addons-countdown',
			'psgTimer',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'volcano-addons-countdown',
			'jquery-psgTimer',
		);
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'volcano-widgets' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_countdown',
			[
				'label' => esc_html__( 'Countdown', 'volcano-addons' ),
			]
		);

		$this->add_control(
			'due_date',
			[
				'label'       => esc_html__( 'Due Date', 'volcano-addons' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'j.n.Y G:i ', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( esc_html__( 'Date set according to your timezone: %s.', 'volcano-addons' ), Utils::get_timezone_string() ),
			]
		);

		$this->add_control(
			'custom_separator',
			[
				'label'     => esc_html__( 'Separator', 'volcano-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					':' => esc_html__( ': (Colon)', 'volcano-addons' ),
					'/' => esc_html__( '/ (Slash)', 'volcano-addons' ),
					'-' => esc_html__( '- (Dash)', 'volcano-addons' ),
					'.' => esc_html__( '. (Dot)', 'volcano-addons' ),
				],
				'default'   => ':',
				'selectors' => [
					'{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers > div:after'             => 'content:"{{VALUE}}" !important',
					'{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers > div:last-child:after ' => 'content:none !important',
				],
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label'     => esc_html__( 'Show Label', 'volcano-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'volcano-addons' ),
				'label_off' => esc_html__( 'Hide', 'volcano-addons' ),
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_placement',
			[
				'label'     => esc_html__( 'Label Placement', 'volcano-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'before'  => esc_html__( 'Before', 'volcano-addons' ),
					'after'   => esc_html__( 'After', 'volcano-addons' ),
					'outside' => esc_html__( 'Outside', 'volcano-addons' ),
				],
				'default'   => 'after',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_days',
			[
				'label'       => esc_html__( 'Days', 'volcano-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Days', 'volcano-addons' ),
				'placeholder' => esc_html__( 'Days', 'volcano-addons' ),
				'condition'   => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_hours',
			[
				'label'       => esc_html__( 'Hours', 'volcano-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Hours', 'volcano-addons' ),
				'placeholder' => esc_html__( 'Hours', 'volcano-addons' ),
				'condition'   => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_minutes',
			[
				'label'       => esc_html__( 'Minutes', 'volcano-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Minutes', 'volcano-addons' ),
				'placeholder' => esc_html__( 'Minutes', 'volcano-addons' ),
				'condition'   => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_seconds',
			[
				'label'       => esc_html__( 'Seconds', 'volcano-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Seconds', 'volcano-addons' ),
				'placeholder' => esc_html__( 'Seconds', 'volcano-addons' ),
				'condition'   => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_digit_style',
			[
				'label' => esc_html__( 'Digit', 'volcano-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'digit_typography',
				'selector' => '{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .number',
			]
		);

		$this->add_control(
			'digit_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'volcano-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'digit_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'volcano-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'digit_border',
				'selector'  => '{{WRAPPER}}  .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .number',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'digit_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'volcano-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}  .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'digit_padding',
			[
				'label'      => esc_html__( 'Padding', 'volcano-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'digit_margin',
			[
				'label'      => esc_html__( 'Margin', 'volcano-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_Label_style',
			[
				'label' => esc_html__( 'Label', 'volcano-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label',
			]
		);

		$this->add_control(
			'label_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'volcano-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'volcano-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_align',
			[
				'label'     => esc_html__( 'Alignment', 'volcano-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'volcano-addons' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'volcano-addons' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Bottom', 'volcano-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'align-self: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'label_border',
				'selector'  => '{{WRAPPER}}  .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'volcano-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}  .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label'      => esc_html__( 'Padding', 'volcano-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_margin',
			[
				'label'      => esc_html__( 'Margin', 'volcano-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .volcano-addons-countdown .psgTimer_numbers .psgTimer_unit .psg-timer--label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separator_style',
			[
				'label' => esc_html__( 'Separator', 'volcano-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'separator_typography',
				// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'exclude'  => [
					'font_family',
					'text_decoration',
					'text_transform',
					'font_style',
					'letter_spacing',
					'word_spacing',
					'font_weight',
				],
				'selector' => '{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers > div:after',
			]
		);

		$this->add_control(
			'separator_text_color',
			[
				'label'     => esc_html__( 'Color', 'volcano-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .volcano-addons-countdown .psgTimer_numbers > div:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->render_controller( 'style-controller-woocommerce-section-header' );

	}

	/**
	 * @throws Exception
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$end_time = new \DateTime( $settings['due_date'] );
		$end_time = $end_time->format( 'j.m.Y H:i' ) . ' ' . Utils::get_timezone_string();


		$attributes = [
			'class'                => 'volcano-addons-countdown',
			'data-timer-end'       => $end_time,
			'data-label-placement' => $settings['label_placement'] ?? 'auto',
		];

		if ( $settings['show_labels'] ) {
			$day     = $settings['label_days'] ?? '';
			$hour    = $settings['label_hours'] ?? '';
			$minutes = $settings['label_minutes'] ?? '';
			$seconds = $settings['label_seconds'] ?? '';

			$attributes = array_merge( $attributes, [
				'data-label-days'    => $day,
				'data-label-hours'   => $hour,
				'data-label-minutes' => $minutes,
				'data-label-seconds' => $seconds,
			] );
		}

		$this->add_render_attribute( 'div', $attributes );
		?>


		<div <?php $this->print_render_attribute_string( 'div' ); ?>></div>
		<?php
	}


}
