/**
 * BLOCK: main
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

// import * as api from './api';

const {MediaUpload, PlainText, RichText} = wp.editor;
const {__} = wp.i18n; // Import __() from wp.i18n
const {registerBlockType, InspectorControls} = wp.blocks; // Import registerBlockType() from wp.blocks
const {Button} = wp.components;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

// Inspired by https://codepen.io/abergin/pen/ihlDf
registerBlockType('vl-gm/main', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __('VL Gutenberg Maps'), // Block title.
	icon: 'location-alt', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__('VL Gutenberg Maps'),
		__('Gutenberg Maps')
	],
	attributes: {
		title: {
			type: 'string',
			selector: '.vl_gm__title'
		},
	},


	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function(props) {

		const {attributes: {title}, setAttributes} = props;

		let settings_values = {
			api_key
		}

		// REMOVE FOR PRODUCTION
		console.log('settings_values.api_key', settings_values.api_key);

		//TODO : if api key return map, else ask to setup api key => <h2>Please enter your google maps api key <a href="/wp-admin/admin.php?page=vl_gm">here</a> before we can load your map.</h2>

		return (
			<div className={ [props.className] }>
     <h1 className={ 'vl_gm__title' }> { title } </h1>
     <RichText keepPlaceholderOnFocus="true" tagName="h1" className={ "vl_gm__title" } label="Gutenberg maps title" help="Enter some text" value={ title }
       name="title" placeholder="Map title" onChange={ (content) => setAttributes({
                                                       	title: content
                                                       }) } />
   </div>
		)

	},


	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function(props) {
		const {attributes: {title}, setAttributes} = props;

		return (
			<div className={ [props.className] }>
     <h1 className={ 'vl_gm__title' }> { title } </h1>
   </div>
		)


	},
});
