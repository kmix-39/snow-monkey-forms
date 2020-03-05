import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import withValidations from '../../hoc/with-validations';

const edit = ( { attributes, setAttributes } ) => {
	const { name, value, options, description } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Attributes', 'snow-monkey-forms' ) }>
					<TextControl
						label={ __( 'name', 'snow-monkey-forms' ) }
						value={ name }
						onChange={ ( attribute ) =>
							setAttributes( { name: attribute } )
						}
					/>

					<TextareaControl
						label={ __( 'options', 'snow-monkey-forms' ) }
						value={ options }
						help={ __(
							'"value" : "label"\u21B5',
							'snow-monkey-forms'
						) }
						onChange={ ( attribute ) =>
							setAttributes( { options: attribute } )
						}
					/>

					<TextControl
						label={ __( 'value', 'snow-monkey-forms' ) }
						value={ value }
						onChange={ ( attribute ) =>
							setAttributes( { value: attribute } )
						}
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Block settings', 'snow-monkey-forms' ) }
				>
					<TextControl
						label={ __( 'Description', 'snow-monkey-forms' ) }
						value={ description }
						onChange={ ( attribute ) =>
							setAttributes( { description: attribute } )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<ServerSideRender
				block="snow-monkey-forms/control-radio-buttons"
				attributes={ { ...attributes, disabled: true } }
			/>
		</>
	);
};

export default compose( withValidations )( edit );