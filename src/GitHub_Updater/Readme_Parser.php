<?php
/**
 * GitHub Updater
 *
 * @package   GitHub_Updater
 * @author    Andy Fragen
 * @license   GPL-2.0+
 * @link      https://github.com/afragen/github-updater
 * @uses      https://meta.trac.wordpress.org/browser/sites/trunk/wordpress.org/public_html/wp-content/plugins/plugin-directory/readme/class-parser.php
 */

namespace Fragen\GitHub_Updater;

use WordPressdotorg\Plugin_Directory\Readme\Parser as Parser;
use Parsedown;

/*
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Readme_Parser
 *
 * @package Fragen\GitHub_Updater
 */
class Readme_Parser extends Parser {

	/**
	 * Constructor.
	 *
	 * @param string $file_contents Contents of file.
	 */
	public function __construct( $file_contents ) {
		if ( $file_contents ) {
			$this->parse_readme( $file_contents );
		}
	}

	/**
	 * @param string $text
	 *
	 * @return string
	 */
	protected function parse_markdown( $text ) {
		static $markdown = null;

		if ( is_null( $markdown ) ) {
			$markdown = new Parsedown();
		}

		return $markdown->text( $text );
	}

	/**
	 * @return array
	 */
	public function parse_data() {
		$data = array();
		foreach ( $this as $key => $value ) {
			$data[ $key ] = $value;
		}

		return $data;
	}

	/**
	 * @param array $users
	 *
	 * @return array
	 */
	protected function sanitize_contributors( $users ) {
		return $users;
	}

	/**
	 * Makes generation of short description PHP 5.3 compliant.
	 * Original requires PHP 5.4 for array dereference.
	 *
	 * @return string $description[0]
	 */
	protected function short_description_53() {
		$description = array_filter( explode( "\n", $this->sections['description'] ) );

		return $description[0];
	}

	/**
	 * Converts FAQ from dictionary list to h4 style.
	 */
	protected function faq_as_h4() {
		unset( $this->sections['faq'] );
		$this->sections['faq'] = '';
		foreach ( $this->faq as $question => $answer ) {
			$this->sections['faq'] .= "<h4>{$question}</h4>\n{$answer}\n";
		}
	}

}
