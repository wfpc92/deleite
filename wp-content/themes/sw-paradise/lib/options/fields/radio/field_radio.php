<?php
class SW_PARADISE_Options_radio extends SW_PARADISE_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since SW_PARADISE_Options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since SW_PARADISE_Options 1.0
	*/
	function render(){
		
		$class = (isset($this->field['class']))?'class="'.esc_attr( $this->field['class'] ).'" ':'';
		
		echo '<fieldset>';
			
			foreach($this->field['options'] as $k => $v){
				
				echo '<label for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';
				echo '<input type="radio" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.' value="'.$k.'" '.checked($this->value, $k, false).'/>';
				echo ' <span>'.esc_html( $v ).'</span>';
				echo '</label><br/>';
				
			}//foreach

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<span class="description">'.esc_html( $this->field['desc'] ).'</span>':'';
		
		echo '</fieldset>';
		
	}//function
	
	public function getCpanelHtml(){
		echo ' <div class="control-group">';
		echo '<label class="control-label">'.$this->field['title'].'</label>';
		echo '<div class="controls">';
		$this->render();
		echo '</div></div>';
	}
}//class
?>