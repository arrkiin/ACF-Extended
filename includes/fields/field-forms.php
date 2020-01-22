<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_forms')):

class acfe_field_forms extends acf_field{
    
    function __construct(){
        
        $this->name = 'acfe_forms';
        $this->label = __('Forms', 'acfe');
        $this->category = 'relational';
        $this->defaults = array(
            'post_type'     => array(),
            'field_type'    => 'checkbox',
            'multiple' 		=> 0,
			'allow_null' 	=> 0,
			'choices'		=> array(),
			'default_value'	=> '',
			'ui'			=> 0,
			'ajax'			=> 0,
			'placeholder'	=> '',
            'layout'        => '',
			'toggle'        => 0,
			'allow_custom'  => 0,
			'return_format' => 'name',
        );
        
        parent::__construct();
        
    }

    function prepare_field($field){
        
        $field['choices'] = acfe_get_pretty_forms($field['forms']);
        
        // Set Field Type
        $field['type'] = $field['field_type'];
        
        return $field;
        
    }
    
    function render_field_settings($field){
        
        if(isset($field['default_value']))
            $field['default_value'] = acf_encode_choices($field['default_value'], false);
        
        // Allow Form
		acf_render_field_setting($field, array(
			'label'			=> __('Allow Forms','acfe'),
			'instructions'	=> '',
			'type'			=> 'select',
			'name'			=> 'forms',
			'choices'		=> acfe_get_pretty_forms(),
			'multiple'		=> 1,
			'ui'			=> 1,
			'allow_null'	=> 1,
			'placeholder'	=> __("All forms",'acfe'),
		));
        
        // field_type
        acf_render_field_setting($field, array(
            'label'			=> __('Appearance','acfe'),
            'instructions'	=> __('Select the appearance of this field', 'acfe'),
            'type'			=> 'select',
            'name'			=> 'field_type',
            'optgroup'		=> true,
            'choices'		=> array(
                'checkbox'  => __('Checkbox', 'acfe'),
                'radio'     => __('Radio Buttons', 'acfe'),
                'select'    => _x('Select', 'noun', 'acfe')
            )
        ));
        
        // default_value
		acf_render_field_setting($field, array(
			'label'			=> __('Default Value','acfe'),
			'instructions'	=> __('Enter each default value on a new line','acfe'),
			'name'			=> 'default_value',
			'type'			=> 'textarea',
		));
        
        // return_format
        acf_render_field_setting($field, array(
            'label'			=> __('Return Value', 'acfe'),
            'instructions'	=> '',
            'type'			=> 'radio',
            'name'			=> 'return_format',
            'choices'		=> array(
                'id'    =>	__('Form ID', 'acfe'),
                'name'  =>	__('Form name', 'acfe')
            ),
            'layout'	=>	'horizontal',
        ));
        
		// Select + Radio: allow_null
		acf_render_field_setting($field, array(
			'label'			=> __('Allow Null?','acfe'),
			'instructions'	=> '',
			'name'			=> 'allow_null',
			'type'			=> 'true_false',
			'ui'			=> 1,
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                ),
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'radio',
                    ),
                ),
            )
		));
        
        // placeholder
        acf_render_field_setting($field, array(
            'label'			=> __('Placeholder Text','acfe'),
            'instructions'	=> __('Appears within the input','acfe'),
            'type'			=> 'text',
            'name'			=> 'placeholder',
            'placeholder'   => _x('Select', 'verb', 'acfe'),
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'allow_null',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                    
                ),
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'ui',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                    
                ),
            )
        ));
        
        // Select: multiple
		acf_render_field_setting($field, array(
			'label'			=> __('Select multiple values?','acfe'),
			'instructions'	=> '',
			'name'			=> 'multiple',
			'type'			=> 'true_false',
			'ui'			=> 1,
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                ),
            )
		));
        
        // Select: ui
		acf_render_field_setting($field, array(
			'label'			=> __('Stylised UI','acfe'),
			'instructions'	=> '',
			'name'			=> 'ui',
			'type'			=> 'true_false',
			'ui'			=> 1,
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                ),
            )
		));
				
		
		// Select: ajax
		acf_render_field_setting($field, array(
			'label'			=> __('Use AJAX to lazy load choices?','acfe'),
			'instructions'	=> '',
			'name'			=> 'ajax',
			'type'			=> 'true_false',
			'ui'			=> 1,
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'ui',
                        'operator'  => '==',
                        'value'     => 1,
                    ),
                ),
            )
		));
		
		// Radio: other_choice
		acf_render_field_setting($field, array(
			'label'			=> __('Other','acfe'),
			'instructions'	=> '',
			'name'			=> 'other_choice',
			'type'			=> 'true_false',
			'ui'			=> 1,
			'message'		=> __("Add 'other' choice to allow for custom values", 'acfe'),
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'radio',
                    ),
                ),
            )
		));
		
		
		// Radio: save_other_choice
		acf_render_field_setting($field, array(
			'label'			=> __('Save Other','acfe'),
			'instructions'	=> '',
			'name'			=> 'save_other_choice',
			'type'			=> 'true_false',
			'ui'			=> 1,
			'message'		=> __("Save 'other' values to the field's choices", 'acfe'),
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'radio',
                    ),
                    array(
                        'field'     => 'other_choice',
                        'operator'  => '==',
                        'value'     => 1,
                    ),
                ),
            )
		));
        
        // Checkbox: layout
		acf_render_field_setting($field, array(
			'label'			=> __('Layout','acfe'),
			'instructions'	=> '',
			'type'			=> 'radio',
			'name'			=> 'layout',
			'layout'		=> 'horizontal', 
			'choices'		=> array(
				'vertical'		=> __("Vertical",'acfe'), 
				'horizontal'	=> __("Horizontal",'acfe')
			),
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'checkbox',
                    ),
                ),
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'radio',
                    ),
                ),
            )
		));
        
        // Checkbox: toggle
        acf_render_field_setting($field, array(
			'label'			=> __('Toggle','acfe'),
			'instructions'	=> __('Prepend an extra checkbox to toggle all choices','acfe'),
			'name'			=> 'toggle',
			'type'			=> 'true_false',
			'ui'			=> 1,
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'checkbox',
                    ),
                ),
            )
		));
        
        // Checkbox: other_choice
		acf_render_field_setting($field, array(
			'label'			=> __('Allow Custom','acfe'),
			'instructions'	=> '',
			'name'			=> 'allow_custom',
			'type'			=> 'true_false',
			'ui'			=> 1,
			'message'		=> __("Allow 'custom' values to be added", 'acfe'),
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'checkbox',
                    ),
                ),
            )
		));
		
		
		// Checkbox: save_other_choice
		acf_render_field_setting($field, array(
			'label'			=> __('Save Custom','acfe'),
			'instructions'	=> '',
			'name'			=> 'save_custom',
			'type'			=> 'true_false',
			'ui'			=> 1,
			'message'		=> __("Save 'custom' values to the field's choices", 'acfe'),
            'conditions' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'checkbox',
                    ),
                    array(
                        'field'     => 'allow_custom',
                        'operator'  => '==',
                        'value'     => 1,
                    ),
                ),
            )
		));
        
    }
    
    
    function format_value($value, $post_id, $field){
        
        // Return: name
		if($field['return_format'] === 'name'){
            
            // array
            if(acf_is_array($value)){
                
                foreach($value as $i => $v){
                    
                    $form_name = get_field('acfe_form_name', $v);
                    
                    $value[$i] = $form_name;
                    
                }
            
            // string
            }else{
                
                $value = get_field('acfe_form_name', $value);
                
            }
            
        }
        
		// return
		return $value;
        
    }
    
}

// initialize
acf_register_field_type('acfe_field_forms');

endif;
