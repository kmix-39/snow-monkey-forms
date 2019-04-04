<?php
/**
 * Plugin name: Snow Monkey Forms
 * Version: 0.0.1
 *
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\Forms;

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Helper;

define( 'SNOW_MONKEY_FORMS_URL', plugin_dir_url( __FILE__ ) );
define( 'SNOW_MONKEY_FORMS_PATH', plugin_dir_path( __FILE__ ) );

require_once( SNOW_MONKEY_FORMS_PATH . '/vendor/autoload.php' );

class Bootstrap {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_plugins_loaded' ] );
	}

	public function _plugins_loaded() {
		add_shortcode( 'snow_monkey_form', [ $this, '_shortcode_form' ] );
		add_action( 'wp_enqueue_scripts', [ $this, '_enqueue_assets' ] );
		add_action( 'rest_api_init', [ $this, '_endpoint' ] );
	}

	public function _shortcode_form( $attributes ) {
		$attributes = shortcode_atts(
			[
				'id' => null,
			],
			$attributes
		);

		if ( ! $attributes['id'] ) {
			return;
		}

		$form_id = $attributes['id'];
		$setting = DataStore::get( $form_id );

		if ( ! $setting->get( 'controls' ) ) {
			return;
		}

		ob_start();
		?>
		<form class="snow-monkey-form" id="snow-monkey-form-<?php echo esc_attr( $form_id ); ?>" method="post" action="">
			<div class="p-entry-content">
				<?php foreach ( $setting->get( 'controls' ) as $control ) : ?>
					<p>
						<?php echo esc_html( $control['label'] ); ?><br>
						<span class="snow-monkey-form__placeholder" data-name="<?php echo esc_attr( $control['attributes']['name'] ); ?>">
							<?php echo Helper::control( $control['type'], $control ); ?>
						</span>
					</p>
				<?php endforeach; ?>

				<p class="snow-monkey-form__action">
					<?php echo Helper::control( 'button', [ 'attributes' => [ 'value' => '確認', 'data-action' => 'confirm' ] ] ); ?>
					<?php echo Helper::control( 'hidden', [ 'attributes' => [ 'name' => '_method', 'value' => 'confirm' ] ] ); ?>
				</p>
			</div>
			<?php echo Helper::control( 'hidden', [ 'attributes' => [ 'name' => '_formid', 'value' => $form_id ] ] ); ?>
		</form>
		<?php
		return ob_get_clean();
	}

	public function _enqueue_assets() {
		wp_enqueue_script(
			'snow-monkey-forms',
			SNOW_MONKEY_FORMS_URL . '/dist/js/app.min.js',
			[ 'jquery' ],
			filemtime( SNOW_MONKEY_FORMS_PATH . '/dist/js/app.min.js' ),
			true
		);

		wp_add_inline_script(
			'snow-monkey-forms',
			'var snow_monkey_forms = ' . json_encode(
				[
					'view_json_url' => home_url() . '/wp-json/snow-monkey-form/v1/view',
				]
			),
			'before'
		);
	}

	public function _endpoint() {
		register_rest_route(
			'snow-monkey-form/v1',
			'/view',
			[
				'methods'  => 'POST',
				'callback' => function() {
					ob_start();
					include( SNOW_MONKEY_FORMS_PATH . '/endpoint/view.php' );
					return ob_get_clean();
				},
			]
		);
	}
}

new Bootstrap();
