<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms\App\Control;

use Snow_Monkey\Plugin\Forms\App\Contract;
use Snow_Monkey\Plugin\Forms\App\Helper;

class Tel extends Contract\Control {

	/**
	 * @var array
	 *  - string  name
	 *  - string  value
	 *  - string  placeholder
	 *  - boolean disabled
	 *  - int     maxlength
	 *  - int     size
	 *  - string  autocomplete
	 *  - string  id
	 *  - string  class
	 *  - boolean data-invalid
	 */
	protected $attributes = [
		'name'         => '',
		'value'        => '',
		'placeholder'  => '',
		'disabled'     => false,
		'maxlength'    => 0,
		'size'         => 0,
		'autocomplete' => 'tel-national',
		'id'           => '',
		'class'        => 'smf-text-control__control',
		'data-invalid' => false,
	];

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var array
	 */
	protected $validations = [];

	/**
	 * Save the value.
	 *
	 * @param mixed $value The value to be saved.
	 */
	public function save( $value ) {
		$this->set_attribute( 'value', ! is_array( $value ) ? $value : '' );
	}

	/**
	 * Return HTML for input page.
	 *
	 * @return string
	 */
	public function input() {
		$attributes = $this->get_property( 'attributes' );
		$attributes = $this->_normalize_attributes( $attributes );

		$description = $this->get_property( 'description' );
		if ( $description ) {
			$description = sprintf(
				'<div class="smf-control-description">%1$s</div>',
				wp_kses_post( $description )
			);
		}

		return sprintf(
			'<div class="smf-text-control">
				<input type="tel" %1$s>
			</div>
			%2$s',
			$this->_generate_attributes_string( $attributes ),
			$description
		);
	}

	/**
	 * Return HTML for confirm page.
	 *
	 * @return string
	 */
	public function confirm() {
		return sprintf(
			'%1$s%2$s',
			esc_html( $this->get_attribute( 'value' ) ),
			Helper::control(
				'hidden',
				[
					'attributes' => [
						'name'  => $this->get_attribute( 'name' ),
						'value' => $this->get_attribute( 'value' ),
					],
				]
			)->confirm()
		);
	}

	/**
	 * Return invalid message.
	 *
	 * @param string $message The message to be displayed.
	 * @return string
	 */
	public function invalid( $message = '' ) {
		$this->set_attribute( 'data-invalid', true );

		return sprintf(
			'%1$s
			<div class="smf-error-messages">
				%2$s
			</div>',
			$this->input(),
			$message
		);
	}
}
