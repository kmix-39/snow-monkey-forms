<?php
/**
 * @package snow-monkey-forms
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\Forms\App\DataStore;
use Snow_Monkey\Plugin\Forms\App\Model\Responser;
use Snow_Monkey\Plugin\Forms\App\Model\Validator;
use Snow_Monkey\Plugin\Forms\App\Model\Dispatcher;
use Snow_Monkey\Plugin\Forms\App\Model\MailParser;
use Snow_Monkey\Plugin\Forms\App\Model\Mailer;

$data    = filter_input_array( INPUT_POST );
$form_id = $data['_formid'];
$setting = DataStore::get( $form_id );

$responser = new Responser( $data );
$validator = new Validator( $responser, $setting );

if ( ! $validator->validate() ) {
	$data['_method'] = 'error';
}

$controller = Dispatcher::dispatch( $data['_method'], $responser, $setting, $validator );
$controller->send();

if ( 'complete' === $data['_method'] ) {
	$mail_parser = new MailParser( $responser );

	$mailer = new Mailer(
		[
			'to'      => $setting->get( 'administrator_email_to' ),
			'subject' => $setting->get( 'administrator_email_subject' ),
			'body'    => $mail_parser->parse( $setting->get( 'administrator_email_body' ) ),
		]
	);
	$mailer->send();
}
