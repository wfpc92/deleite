<?php
if ( ! class_exists('SW_PARADISE_Options') ){

	class SW_PARADISE_Options{

		public $dir = SW_PARADISE_DIR;
		public $url = SW_PARADISE_URL;
		public $page = '';
		public $args = array();
		public $sections = array();
		public $extra_tabs = array();
		public $errors = array();
		public $warnings = array();
		public $options = array();

		protected $option_name;

		/**
		 * Class Constructor. Defines the args for the theme options class
		 *
		 * @since SW_PARADISE_Options 1.0
		 *
		 * @param $array $args Arguments. Class constructor arguments.
		 */
		public function __construct($sections = array(), $args = array()){
				
			$defaults = array();
				
			$defaults['opt_name'] = '';//must be defined by theme/plugin
				
				
			$defaults['page_icon'] = 'icon-themes';
			$defaults['page_title'] = esc_html__('Options', 'sw-paradise');
			$defaults['page_slug'] = '_options';
			$defaults['page_cap'] = 'manage_options';
			$defaults['page_type'] = 'submenu';
			$defaults['page_position'] = 100;
			$defaults['allow_sub_menu'] = true;
			
				
			$defaults['show_import_export'] = false;
			$defaults['dev_mode'] = false;
			$defaults['stylesheet_override'] = false;
				
			//get args
			$this->args = wp_parse_args( $args, $defaults );
			$this->args = apply_filters( 'sw_paradise_options_args_' . $this->args['opt_name'], $this->args );

			if (!isset($this->args['opt_name'])) {
				$this->args['opt_name'] = $this->getOptionName();
			}
			
			//get sections
			$this->sections = apply_filters( 'sw_paradise_options_sections_' . $this->args['opt_name'], $sections );				
			
			//set option with defaults
			add_action('init', array(&$this, '_set_default_options'));
				
			//options page
			add_action('admin_menu', array(&$this, '_options_page'));
				
			//register setting
			add_action('admin_init', array(&$this, '_register_setting'));
				
			//add the js for the error handling before the form
			add_action('sw-paradise-opts-page-before-form-'.$this->args['opt_name'], array(&$this, '_errors_js'), 1);
				
			//add the js for the warning handling before the form
			add_action('sw-paradise-opts-page-before-form-'.$this->args['opt_name'], array(&$this, '_warnings_js'), 2);
				
			//hook into the wp feeds for downloading the exported settings
			add_action('do_feed_yaopts-'.$this->args['opt_name'], array(&$this, '_download_options'), 1, 1);
				
			//get the options for use later on
			$this->options = get_option($this->args['opt_name']);
			
			$this->cleanCookie();
			
			add_action('wp_footer', array(&$this, 'print_cpanel'));
		} //function
		
		public function print_cpanel(){
			if ( !is_admin() && !is_customize() && $this->get('show_cpanel', 0) ){
				$this->cpanel();
			}
		}
		
		public function cleanCookie() {
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('sw-paradise-opts-saved') == '1' && is_array($_COOKIE) ){
				
				foreach ( $_COOKIE as $name => $val ){
					$key = $this->args['opt_name'];
					if ( preg_match("/^$key/", $name, $m) ){
						setcookie($name, 1, time() - 3600, SITECOOKIEPATH, COOKIE_DOMAIN);
					}
				}
			}
		}
		
		public function getOptionName(){
			return SW_PARADISE_OPTION;
		}
		
		public function isSettingUpdated(){
			return isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true';
		}
		
		/**
		 * ->get(); This is used to return and option value from the options array
		 * @param string $opt_name
		 * @return mixed
		 *
		 */
		
		public function get($opt_name, $default = null){
			if ( !is_admin() && !is_customize() && isset($this->options['show_cpanel']) && $this->options['show_cpanel']){
				$cookie_opt_name = $this->args['opt_name'].'_'.$opt_name;
				if ( array_key_exists($cookie_opt_name, $_COOKIE) ){
					return $_COOKIE[$cookie_opt_name];
				}
			}
			if( is_array($this->options) ){
				if ( array_key_exists($opt_name, $this->options) ){
					return $this->options[$opt_name];
				}
			}
			return $default;
		}
		
		/**
		 * @deprecated use get($opt_name) instead of
		 * @param string $opt_name
		 * @return Ambigous <unknown, multitype:>
		 */
		public function getCpanelValue( $opt_name = null ){
			return $this->get($opt_name);
		}
		
		/**
		 * ->set(); This is used to set an arbitrary option in the options array
		 *
		 * @since SW_PARADISE_Options 1.0.1
		 *
		 * @param string $opt_name the name of the option being added
		 * @param mixed $value the value of the option being added
		 */
		function set($opt_name = '', $value = '') {
			if($opt_name != ''){
				$this->options[$opt_name] = $value;
				update_option($this->args['opt_name'], $this->options);
			}//if
		}
		
		public function cpanel(){
			if ( !isset($this->_cpanel) ){
				$this->_cpanel = true;
				$this->_options_form();
				add_action('wp_enqueue_scripts', array(&$this, '_enqueue'));
			}
		}

		/**
		 * ->show(); This is used to echo and option value from the options array
		 *
		 * @since SW_PARADISE_Options 1.0.1
		 *
		 * @param $array $args Arguments. Class constructor arguments.
		 */
		function show($opt_name, $default = ''){
			$option = $this->get($opt_name);
			if(!is_array($option) && $option != ''){
				echo $option;
			}elseif($default != ''){
				echo $default;
			}
		}//function



		/**
		 * Get default options into an array suitable for the settings API
		 *
		 * @since SW_PARADISE_Options 1.0
		 *
		 */
		function _default_values(){
			$defaults = array();
				
			foreach( $this->sections as $i => $section ){

				if( isset($section['fields']) && is_array($section['fields']) ){
						
					foreach( $section['fields'] as $j => $field ){

						if( !isset($field['std']) ){
							$field['std'] = '';
						}
							
						$defaults[ $field['id'] ] = $field['std'];

					}//foreach

				} //if

			}//foreach
				
			//fix for notice on first page load
			$defaults['last_tab'] = 0;

			return $defaults;
		}



		/**
		 * Set default options on admin_init if option doesnt exist (theme activation hook caused problems, so admin_init it is)
		 *
		 * @since SW_PARADISE_Options 1.0
		 *
		 */
		function _set_default_options(){
			if( !get_option( $this->args['opt_name'] ) ){
				add_option( $this->args['opt_name'], $this->_default_values() );
			}
			$this->options = get_option( $this->args['opt_name'] );
		}


		/**
		 * Class Theme Options Page Function, creates main options page.
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _options_page(){

			$this->page = add_theme_page(
				$this->args['page_title'],
				$this->args['menu_title'],
				$this->args['page_cap'],
				$this->args['page_slug'],
				array(&$this, '_options_page_html')
			);

			add_action('admin_print_styles-'.$this->page, array(&$this, '_enqueue'));
			add_action('load-'.$this->page, array(&$this, '_load_page'));
		}//function


		/**
		 * enqueue styles/js for theme page
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _enqueue(){
				
			wp_enqueue_style(
				'sw-paradise-opts-css',
				SW_PARADISE_URL . '/admin/css/options.css',
				array('farbtastic'),
				time(),
				'all'
			);

			wp_enqueue_script(
				'sw-paradise-opts-js',
				SW_PARADISE_URL.'/admin/js/options.js',
				array('jquery'),
				time(),
				true
			);
			
			wp_localize_script('sw-paradise-opts-js', 'sw_paradise_opts', array('reset_confirm' => esc_html__('Are you sure? Resetting will loose all custom values.', 'sw-paradise'), 'opt_name' => $this->args['opt_name']));
				
			foreach($this->sections as $k => $section){
				if(isset($section['fields'])){
					foreach($section['fields'] as $fieldk => $field){
						$field_instance = $this->getFieldInstance($field);
						if ( method_exists($field_instance, 'enqueue') ){
							$field_instance->enqueue();
						}
					}//foreach
				}//if fields
			}//foreach
		}//function

		/**
		 * Download the options file, or display it
		 *
		 * @since SW_PARADISE_Options 1.0.1
		 */
		function _download_options(){
			if(!isset($_GET['secret']) || $_GET['secret'] != md5(AUTH_KEY.SECURE_AUTH_KEY)){
				wp_die('Invalid Secret for options use');exit;
			}
			if(!isset($_GET['feed'])){
				wp_die('No Feed Defined');exit;
			}
			$backup_options = get_option(str_replace('yaopts-','',$_GET['feed']));
			$backup_options['sw-paradise-opts-backup'] = '1';
			$content = '###'.serialize($backup_options).'###';
				
				
			if(isset($_GET['action']) && $_GET['action'] == 'download_options'){
				header('Content-Description: File Transfer');
				header('Content-type: application/txt');
				header('Content-Disposition: attachment; filename="'.str_replace('yaopts-','',$_GET['feed']).'_options_'.date('d-m-Y').'.txt"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				echo $content;
				exit;
			}else{
				echo $content;
				exit;
			}
		}




		/**
		 * show page help
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _load_page(){
				
			//do admin head action for this page
			add_action('admin_head', array(&$this, 'admin_head'));
				
			$screen = get_current_screen();
				
			do_action('sw-paradise-opts-load-page-'.$this->args['opt_name'], $screen);
				
		}//function


		/**
		 * do action sw-paradise-opts-admin-head for theme options page
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function admin_head(){
				
			do_action('sw-paradise-opts-admin-head-'.$this->args['opt_name'], $this);
				
		}//function


		/**
		 * Register Option for use
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _register_setting(){
				
			register_setting($this->args['opt_name'].'_group', $this->args['opt_name'], array(&$this,'_validate_options'));
				
			foreach($this->sections as $k => $section){

				add_settings_section($k.'_section', $section['title'], array(&$this, '_section_desc'), $k.'_section_group');

				if(isset($section['fields'])){

					foreach($section['fields'] as $fieldk => $field){

						if(isset($field['title'])){

							$th = (isset($field['sub_desc']))?$field['title'].'<span class="description">'.$field['sub_desc'].'</span>':$field['title'];
						}else{
							$th = '';
						}

						add_settings_field($fieldk.'_field', $th, array(&$this,'_field_input'), $k.'_section_group', $k.'_section', $field); // checkbox

					}//foreach

				}

			}//foreach
				
			do_action('sw-paradise-opts-register-settings-'.$this->args['opt_name']);
				
		}//function



		/**
		 * Validate the Options options before insertion
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _validate_options($plugin_options){
				
			set_transient('sw-paradise-opts-saved', '1', 1000 );
				
			if(!empty($plugin_options['import'])){

				if($plugin_options['import_code'] != ''){
					$import = $plugin_options['import_code'];
				}elseif($plugin_options['import_link'] != ''){
					$import = wp_remote_retrieve_body( wp_remote_get($plugin_options['import_link']) );
				}

				$imported_options = unserialize(trim($import,'###'));
				if(is_array($imported_options) && isset($imported_options['sw-paradise-opts-backup']) && $imported_options['sw-paradise-opts-backup'] == '1'){
					$imported_options['imported'] = 1;
					return $imported_options;
				}


			}
				
				
			if(!empty($plugin_options['defaults'])){
				$plugin_options = $this->_default_values();
				return $plugin_options;
			}//if set defaults

				
			//validate fields (if needed)
			$plugin_options = $this->_validate_values($plugin_options, $this->options);
				
			if($this->errors){
				set_transient('sw-paradise-opts-errors-'.$this->args['opt_name'], $this->errors, 1000 );
			}//if errors
				
			if($this->warnings){
				set_transient('sw-paradise-opts-warnings-'.$this->args['opt_name'], $this->warnings, 1000 );
			}//if errors
				
			do_action('sw-paradise-opts-options-validate-'.$this->args['opt_name'], $plugin_options, $this->options);
				
				
			unset($plugin_options['defaults']);
			unset($plugin_options['import']);
			unset($plugin_options['import_code']);
			unset($plugin_options['import_link']);
				
			return $plugin_options;

		}//function




		/**
		 * Validate values from options form (used in settings api validate function)
		 * calls the custom validation class for the field so authors can override with custom classes
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _validate_values($plugin_options, $options){
			foreach($this->sections as $k => $section){

				if(isset($section['fields'])){

					foreach($section['fields'] as $fieldk => $field){
						$field['section_id'] = $k;

						if(isset($field['type']) && $field['type'] == 'multi_text'){
							continue;
						}//we cant validate this yet

						if(!isset($plugin_options[$field['id']]) || $plugin_options[$field['id']] == ''){
							continue;
						}

						//force validate of custom filed types

						if(isset($field['type']) && !isset($field['validate'])){
							if($field['type'] == 'color' || $field['type'] == 'color_gradient'){
								$field['validate'] = 'color';
							}elseif($field['type'] == 'date'){
								$field['validate'] = 'date';
							}
						}//if

						if(isset($field['validate'])){
							$validate = 'SW_PARADISE_Validation_'.$field['validate'];
							
							if(!class_exists($validate)){
								include_once (get_template_directory().'/lib/options/validation/'.$field['validate'].'/validation_'.$field['validate'].'.php');
							}//if
								
							if(class_exists($validate)){
								$validation = new $validate($field, $plugin_options[$field['id']], $options[$field['id']]);
								$plugin_options[$field['id']] = $validation->value;
								if(isset($validation->error)){
									$this->errors[] = $validation->error;
								}
								if(isset($validation->warning)){
									$this->warnings[] = $validation->warning;
								}
								continue;
							}//if
						}//if


						if(isset($field['validate_callback']) && function_exists($field['validate_callback'])){
								
							$callbackvalues = call_user_func($field['validate_callback'], $field, $plugin_options[$field['id']], $options[$field['id']]);
							$plugin_options[$field['id']] = $callbackvalues['value'];
							if(isset($callbackvalues['error'])){
								$this->errors[] = $callbackvalues['error'];
							}//if
							if(isset($callbackvalues['warning'])){
								$this->warnings[] = $callbackvalues['warning'];
							}//if
								
						}//if


					}//foreach

				}

			} //foreach
			return $plugin_options;
		} //function

		public function _options_form(){
			echo '<form method="post" id="cpanel-form" action="'.esc_url( home_url( '/' ) ).'" enctype="multipart/form-data" class="form-horizontal">';
			
			
			$this->options['last_tab'] = (isset($_GET['tab']) && !get_transient('sw-paradise-opts-saved'))?$_GET['tab']:$this->options['last_tab'];
			
			echo '<input type="hidden" id="last_tab" name="'.$this->args['opt_name'].'[last_tab]" value="'.esc_attr( $this->options['last_tab'] ).'" />';
			echo '<script type="text/javascript"> cpanel_name = "'.esc_attr( $this->args['opt_name'] ).'"; </script>';
			echo '<div class="accordion cpanel-inner" id="cpanel">';
			echo '<div class="cpanel-title"><h4> Theme Settings</h4></div>';
			$i = 0;
			foreach($this->sections as $k => $section){
				
				if ( isset($section['fields']) && $this->getCpanelCheck($section['fields']) === true ){
					$icon = (!isset($section['icon']))?'<img src="'.$this->url.'img/glyphicons/glyphicons_019_cogwheel.png" alt="" width="30" height="24"/> ':'<img src="'.esc_attr( $section['icon'] ).'" alt="" width="30" height="24" /> ';
					$section_id = 'cpanel_'.$i++;
				?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#cpanel" href="<?php echo "#$section_id"; ?>">
							<?php echo $icon . esc_html( $section['title'] ); ?>
							</a>
						</div>
						<div id="<?php echo esc_attr( $section_id ); ?>" class="panel-collapse collapse<?php echo $i==1 ? ' in' : ''; ?>">
							<div class="panel-body">
							<?php
								if ( !isset($section['fields']) || empty($section['fields']) ){
									echo '<p>'. esc_html( $section['desc'] ) .'</p>';
								} else {
									foreach ($section['fields'] as $field){
										if ( !$this->get($field['id'].'_cpanel_allow') ) continue;
										$obj_field = $this->getCpanelField($field);
										if ( is_callable( array($obj_field, 'getCpanelHtml') )){
											echo $obj_field->getCpanelHtml();
										} else {
											echo '<p>Please implement '.get_class($obj_field).'::getCpanelHtml()</p>';
										}
									}
								}
							?>
							</div>
						</div>
					</div>
				<?php
				}
			} ?>
			<div class="cpannel-button">
				<button id="cpanel-submit" class="btn btn-inverse" type="submit"><?php esc_html_e( 'SAVE', 'sw-paradise' ); ?></button>
				<button id="cpanel-reset" class="btn btn-inverse" type="button"><?php esc_html_e( 'RESET', 'sw-paradise' ); ?></button>
			</div>
			<?php
			echo '</div>';
			echo '<a class="cpanel-control" href="#cpanel-form"></a>';
			echo '</form>';
		}
		
		public function getFieldInstance( $field = array() ){
			if ( !isset($field['type']) ){
				$field['type'] = 'text';
			}
			$type = $field['type'];
			$classname = __CLASS__ . '_' . $type;
			if ( !class_exists($classname) ){
				$classfile = SW_PARADISE_DIR."/options/fields/$type/field_$type.php";
				if ( file_exists($classfile) )
					include $classfile;
			}
			if ( !class_exists($classname) ){
				return $this;
			}
			$default = array_key_exists('std', $field) ? $field['std'] : null;
			$value_of_field = $this->get( $field['id'], $default );
			
			return new $classname($field, $value_of_field, $this);
		}
		
		public function enqueue(){
			// avoid
		}
		public function getCpanelField( $field ){
			if ( !isset($field['type']) ) $field['type'] = 'text';
			$classname = 'SW_PARADISE_Options_'.$field['type'];
			if ( !class_exists($classname) ){
				$classfile = SW_PARADISE_DIR.'/options/fields/'.$field['type'].'/field_'.$field['type'].'.php';
				if ( file_exists($classfile) ){
					include $classfile;
				}
			}
			if ( !class_exists($classname) ){
				return '';
			}
			$field_value = $this->getCpanelValue( $field['id'] );
			
			return new $classname($field, $field_value, $this);
		}
		
		
		public function getCpanelCheck($fields) {
			foreach ($fields as $field){
				if ( $this->get($field['id'].'_cpanel_allow') ) return true;
			}
			
			return false;
		}
		/**
		 * HTML OUTPUT.
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		

		
		function _options_page_html(){
		
			echo '<div class="wrap">';
			
			echo '<div id="'.esc_attr( $this->args['page_icon'] ).'" class="icon32"><br/></div>';
			
			echo '<h2 id="sw-paradise-opts-heading">'.get_admin_page_title().'</h2>';
			
			echo isset($this->args['intro_text']) ? $this->args['intro_text'] : '';
		
			do_action('sw-paradise-opts-page-before-form-'.$this->args['opt_name']);
		
			echo '<form method="post" action="options.php" enctype="multipart/form-data" id="sw-paradise-options-form">';
			
			settings_fields($this->args['opt_name'].'_group');
		
			$this->options['last_tab'] = (isset($_GET['tab']) && !get_transient('sw-paradise-opts-saved'))?$_GET['tab']:$this->options['last_tab'];
		
			echo '<input type="hidden" id="last_tab" name="'.$this->args['opt_name'].'[last_tab]" value="'.esc_attr( $this->options['last_tab'] ).'" />';
		
			echo '<div id="sw-paradise-opts-header">';
			submit_button('', 'primary', '', false);
			submit_button(esc_html__('Reset to Defaults', 'sw-paradise'), 'secondary', $this->args['opt_name'].'[defaults]', false);
			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';
		
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('sw-paradise-opts-saved') == '1'){
				if(isset($this->options['imported']) && $this->options['imported'] == 1){
					echo '<div id="sw-paradise-opts-imported">'.apply_filters('sw-paradise-opts-imported-text-'.$this->args['opt_name'], wp_kses( __( '<strong>Settings Imported!</strong>', 'sw-paradise'), array( 'strong' => array() ) ) ).'</div>';
				}else{
					echo '<div id="sw-paradise-opts-save">'.apply_filters('sw-paradise-opts-saved-text-'.$this->args['opt_name'], wp_kses( __('<strong>Settings Saved!</strong>', 'sw-paradise'), array( 'strong' => array() ) ) ).'</div>';
					
				}
				delete_transient('sw-paradise-opts-saved');
			}
			echo '<div id="sw-paradise-opts-save-warn">'.apply_filters('sw-paradise-opts-changed-text-'.$this->args['opt_name'], wp_kses( __('<strong>Settings have changed!, you should save them!</strong>', 'sw-paradise'), array( 'strong' => array() ) ) ).'</div>';
			echo '<div id="sw-paradise-opts-field-errors">'. wp_kses( __('<strong><span></span> error(s) were found!</strong>', 'sw-paradise'), array( 'strong' => array(), 'span' => array() ) ).'</div>';
		
			echo '<div id="sw-paradise-opts-field-warnings">'. wp_kses(__('<strong><span></span> warning(s) were found!</strong>', 'sw-paradise'), array( 'strong' => array(), 'span' => array() ) ).'</div>';
		
			echo '<div class="clear"></div><!--clearfix-->';
		
			echo '<div id="sw-paradise-opts-sidebar">';
			echo '<ul id="sw-paradise-opts-group-menu">';
			foreach($this->sections as $k => $section){
				$icon = (!isset($section['icon']))?'<img src="'.$this->url.'img/glyphicons/glyphicons_019_cogwheel.png" alt="" width="30" height="24"/> ':'<img src="'.esc_attr( $section['icon'] ).'" alt="" width="30" height="24"/> ';
				echo '<li id="'.$k.'_section_group_li" class="sw-paradise-opts-group-tab-link-li">';
				echo '<a href="javascript:void(0);" id="'.$k.'_section_group_li_a" class="sw-paradise-opts-group-tab-link-a" data-rel="'.$k.'">'.$icon.'<span>'.$section['title'].'</span></a>';
				echo '</li>';
			}
				
			do_action('sw-paradise-opts-after-section-menu-items-'.$this->args['opt_name'], $this);
			
			echo '<li id="import_export_default_section_group_li" class="sw-paradise-opts-group-tab-link-li">';
			echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="sw-paradise-opts-group-tab-link-a" data-rel="import_export_default"><img src="'.$this->url.'options/img/glyphicons/glyphicons_082_roundabout.png" /> <span>'.esc_html__('Import / Export', 'sw-paradise').'</span></a>';
			echo '</li>';
		
		
			if(true === $this->args['dev_mode']){
				echo '<li id="dev_mode_default_section_group_li" class="sw-paradise-opts-group-tab-link-li">';
				echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="sw-paradise-opts-group-tab-link-a custom-tab" data-rel="dev_mode_default"><img src="'.$this->url.'img/glyphicons/glyphicons_195_circle_info.png" /> <span>'.esc_html__('Dev Mode Info', 'sw-paradise').'</span></a>';
				echo '</li>';
			}//if
		
			echo '</ul>';
			echo '</div>';
		
			echo '<div id="sw-paradise-opts-main">';
		
			foreach($this->sections as $k => $section){
				echo '<div id="'.$k.'_section_group'.'" class="sw-paradise-opts-group-tab">';
				do_settings_sections($k.'_section_group');
				echo '</div>';
			}
		
		
			echo '<div id="import_export_default_section_group'.'" class="sw-paradise-opts-group-tab">';
			echo '<h3>'.esc_html__('Import / Export Options', 'sw-paradise').'</h3>';

			echo '<h4>'.esc_html__('Import Options', 'sw-paradise').'</h4>';

			echo '<p><a href="javascript:void(0);" id="sw-paradise-opts-import-code-button" class="button-secondary">Import from file</a> <a href="javascript:void(0);" id="sw-paradise-opts-import-link-button" class="button-secondary">'. esc_html__( 'Import from URL', 'sw-paradise' ) .'</a></p>';

			echo '<div id="sw-paradise-opts-import-code-wrapper">';

			echo '<div class="sw-paradise-opts-section-desc">';

			echo '<p class="description" id="import-code-description">'.apply_filters('sw-paradise-opts-import-file-description',esc_html__('Input your backup file below and hit Import to restore your sites options from a backup.', 'sw-paradise')).'</p>';

			echo '</div>';

			echo '<textarea id="import-code-value" name="'.$this->args['opt_name'].'[import_code]" class="large-text" rows="8"></textarea>';

			echo '</div>';


			echo '<div id="sw-paradise-opts-import-link-wrapper">';

			echo '<div class="sw-paradise-opts-section-desc">';

			echo '<p class="description" id="import-link-description">'.apply_filters('sw-paradise-opts-import-link-description', esc_html__('Input the URL to another sites options set and hit Import to load the options from that site.', 'sw-paradise')).'</p>';

			echo '</div>';

			echo '<input type="text" id="import-link-value" name="'.$this->args['opt_name'].'[import_link]" class="large-text" value="" />';

			echo '</div>';



			echo '<p id="sw-paradise-opts-import-action"><input type="submit" id="sw-paradise-opts-import" name="'.$this->args['opt_name'].'[import]" class="button-primary" value="'.esc_attr__('Import', 'sw-paradise').'"> <span>'.apply_filters('sw-paradise-opts-import-warning', esc_html__('WARNING! This will overwrite any existing options, please proceed with caution!', 'sw-paradise')).'</span></p>';
			echo '<div id="import_divide"></div>';

			echo '<h4>'.esc_html__('Export Options', 'sw-paradise').'</h4>';
			echo '<div class="sw-paradise-opts-section-desc">';
			echo '<p class="description">'.apply_filters('sw-paradise-opts-backup-description', esc_html__('Here you can copy/download your themes current option settings. Keep this safe as you can use it as a backup should anything go wrong. Or you can use it to restore your settings on this site (or any other site). You also have the handy option to copy the link to yours sites settings. Which you can then use to duplicate on another site', 'sw-paradise')).'</p>';
			echo '</div>';

			echo '<p><a href="javascript:void(0);" id="sw-paradise-opts-export-code-copy" class="button-secondary">'. esc_html__( 'Copy', 'sw-paradise' ) .'</a> <a href="'.add_query_arg(array('feed' => 'yaopts-'.$this->args['opt_name'], 'action' => 'download_options', 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), home_url( '/' ) ).'" id="sw-paradise-opts-export-code-dl" class="button-primary">Download</a> <a href="javascript:void(0);" id="sw-paradise-opts-export-link" class="button-secondary">Copy Link</a></p>';
			$backup_options = $this->options;
			$backup_options['sw-paradise-opts-backup'] = '1';
			$encoded_options = '###'.serialize($backup_options).'###';
			echo '<textarea class="large-text" id="sw-paradise-opts-export-code" rows="8">';print_r($encoded_options);echo '</textarea>';
			echo '<input type="text" class="large-text" id="sw-paradise-opts-export-link-value" value="'.add_query_arg(array('feed' => 'yaopts-'.$this->args['opt_name'], 'secret' => md5(AUTH_KEY.SECURE_AUTH_KEY)), home_url( '/' ) ).'" />';

			echo '</div>';		
		
			if(true === $this->args['dev_mode']){
				echo '<div id="dev_mode_default_section_group'.'" class="sw-paradise-opts-group-tab">';
				echo '<h3>'.esc_html__('Dev Mode Info', 'sw-paradise').'</h3>';
				echo '<div class="sw-paradise-opts-section-desc">';
				echo '<textarea class="large-text" rows="24">'.print_r($this, true).'</textarea>';
				echo '</div>';
				echo '</div>';
			}
		
		
			do_action('sw-paradise-opts-after-section-items-'.$this->args['opt_name'], $this);
		
			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';
			echo '<div class="clear"></div><!--clearfix-->';
		
			echo '<div id="sw-paradise-opts-footer">';
		
			if(isset($this->args['share_icons'])){
				echo '<div id="sw-paradise-opts-share">';
				foreach($this->args['share_icons'] as $link){
					echo '<a href="'.esc_url( $link['link'] ).'" title="'.esc_attr( $link['title'] ).'" target="_blank"><img src="'.esc_attr( $link['img'] ).'"/></a>';
				}
				echo '</div>';
			}
		
			submit_button('', 'primary', '', false);
			submit_button( esc_html__('Reset to Defaults', 'sw-paradise' ), 'secondary', $this->args['opt_name'].'[defaults]', false);
			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div>';
		
			echo '</form>';
		
			do_action('sw-paradise-opts-page-after-form-'.$this->args['opt_name']);
		
			echo '<div class="clear"></div><!--clearfix-->';
			echo '</div><!--wrap-->';
		
		}//function

		/**
		 * JS to display the errors on the page
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _errors_js(){
				
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('sw-paradise-opts-errors-'.$this->args['opt_name'])){
				$errors = get_transient('sw-paradise-opts-errors-'.$this->args['opt_name']);
				$section_errors = array();
				foreach($errors as $error){
					$section_errors[$error['section_id']] = (isset($section_errors[$error['section_id']]))?$section_errors[$error['section_id']]:0;
					$section_errors[$error['section_id']]++;
				}
					
					
				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function(){';
				echo 'jQuery("#sw-paradise-opts-field-errors span").html("'.count($errors).'");';
				echo 'jQuery("#sw-paradise-opts-field-errors").show();';
					
				foreach($section_errors as $sectionkey => $section_error){
					echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"sw-paradise-opts-menu-error\">'.$section_error.'</span>");';
				}
					
				foreach($errors as $error){
					echo 'jQuery("#'.$error['id'].'").addClass("sw-paradise-opts-field-error");';
					echo 'jQuery("#'.$error['id'].'").closest("td").append("<span class=\"sw-paradise-opts-th-error\">'.$error['msg'].'</span>");';
				}
				echo '});';
				echo '</script>';
				delete_transient('sw-paradise-opts-errors-'.$this->args['opt_name']);
			}
				
		}//function



		/**
		 * JS to display the warnings on the page
		 *
		 * @since SW_PARADISE_Options 1.0.3
		 */
		function _warnings_js(){
				
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('sw-paradise-opts-warnings-'.$this->args['opt_name'])){
				$warnings = get_transient('sw-paradise-opts-warnings-'.$this->args['opt_name']);
				$section_warnings = array();
				foreach($warnings as $warning){
					$section_warnings[$warning['section_id']] = (isset($section_warnings[$warning['section_id']]))?$section_warnings[$warning['section_id']]:0;
					$section_warnings[$warning['section_id']]++;
				}
					
					
				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function(){';
				echo 'jQuery("#sw-paradise-opts-field-warnings span").html("'.count($warnings).'");';
				echo 'jQuery("#sw-paradise-opts-field-warnings").show();';
					
				foreach($section_warnings as $sectionkey => $section_warning){
					echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"sw-paradise-opts-menu-warning\">'.$section_warning.'</span>");';
				}
					
				foreach($warnings as $warning){
					echo 'jQuery("#'.$warning['id'].'").addClass("sw-paradise-opts-field-warning");';
					echo 'jQuery("#'.$warning['id'].'").closest("td").append("<span class=\"sw-paradise-opts-th-warning\">'.$warning['msg'].'</span>");';
				}
				echo '});';
				echo '</script>';
				delete_transient('sw-paradise-opts-warnings-'.$this->args['opt_name']);
			}
				
		}//function





		/**
		 * Section HTML OUTPUT.
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _section_desc($section){
				
			$id = rtrim($section['id'], '_section');
			echo '<table class="sw-paradise-opts-section-desc"><tr><td>';

			if(isset($this->sections[$id]['desc']) && !empty($this->sections[$id]['desc'])) {
				echo $this->sections[$id]['desc'];
			}
				
			echo '</td>';
				
			if (isset($this->sections[$id]['fields'])) {
				echo '<td class="customize_allow">Customize</td><td class="cpanel_allow">Cpanel</td>';
			}
				
			echo '</tr></table>';
				
		}//function



		public function prepare_field( $type = ''){
			if (!empty($type)){
				$type_class = 'SW_PARADISE_Options_'.$type;
				if ( !class_exists($type_class) ){
					$type_class_file = $this->dir.'fields/'.$type.'/field_'.$type.'.php';
					file_exists($type_class_file) && require_once $type_class_file;
				}
				return class_exists($type_class);
			}
			return false;
		}
		
		/**
		 * Field HTML OUTPUT.
		 *
		 * Gets option from options array, then calls the speicfic field type class - allows extending by other devs
		 *
		 * @since SW_PARADISE_Options 1.0
		 */
		function _field_input($field){
			
			if ( isset($field['type']) && $this->prepare_field($field['type']) ){
				$field_class = 'SW_PARADISE_Options_'.$field['type'];;
				
				$value = $this->get($field['id']);
									
				if ( !isset($field['sub_option']) ) echo '<table class="field-table"><tr>';
					
				if ( isset($field['sub_option']) ) echo '<td class="customize_allow">';
				else echo '<td>';
					
				$render = '';
				$render = new $field_class($field, $value, $this);
				$render->render();
				!isset($field['sub_option']) && do_action('sw-paradise-opts-rights', $field, $this);
				echo '</td>';
					
				if ( !isset($field['sub_option']) ) echo '</tr></table>';
										
			} // 
		} // function

	} //class
} //if
?>