<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_form
{
	protected $ci;
	protected $col = 12;
	protected $label_col = 2;

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->helper('form');
	}

	private function form_common($type = 'text', $name = '', $label = '', $value = '', $help = '', $attrs = array())
	{
		$defaults = array(
			'type' => $type, 
			'name' => $name, 
			'value' => $value,
			'id'=>'id_'.rand(0,9999));

		$output = '';

		if(!empty($attrs['is_x_editable'])){

			unset($attrs['is_x_editable']);

			$attrs['id'] = empty($attrs['id']) ? $defaults['id'] : $attrs['id'];

			$attrs['class'] = 'myeditable editable editable-click editable-empty ' . @$attrs['class'];

			$output = '<div class="input-group col-sm-'.$this->col.'">';

			$output.= '<a href="#"' . _parse_form_attributes($attrs, $defaults) . ' >' . $value . '</a>';

			$output.= '</div>';

			if(empty($attrs['override_jscript'])){

				$output.= '
				<script type="text/javascript">
					$(function(){
						
						$("#' . $attrs['id'] . '").editable({
							url : admin_url + "' . @$attrs['data-ajax_call_url'] . '",
							params: function(params) {
								params.type = $(this).data("type-data");
								return params;},
								success: function(response, newValue) {
									if(!response.success) return response.msg;
								},
								defaultValue: "",
							});
						});
					</script>';
			}
		}
		else{

			$attrs['class'] = 'form-control '.@$attrs['class'];

			$output = '<div class="input-group col-sm-12">';

			$output.= 	$this->addon_begin(@$attrs['addon_begin']);

			$addon_end = $this->addon_end(@$attrs['addon_end']);
			unset($attrs['addon_begin']);
			unset($attrs['addon_end']);


			$output.= 	'<input ' . _parse_form_attributes($attrs, $defaults) . '>';

			$output.= 	$addon_end;

			$output.= '</div>';
		}

		$html = '';

		if(empty($attrs['none_label'])) $html.= $this->formGroup_begin(0,$label);

		$html.= $output;

		if($help != ''){

			$html.= '<p class="help-block">' . $help . '</p>';
		}

		if(empty($attrs['none_label'])) $html.= $this->formGroup_end();

		return $html;
	}

	public function formGroup_begin($id=0, $label = '')
	{
		$group_col = $this->col - $this->label_col;
		return '<div class="form-group">'.
		(empty($label) ? '' : '<label for="inputEmail3" class="col-sm-'.$this->label_col.' control-label">'.$label.'</label>').
		'<div class="form-group col-md-'.$group_col.'">';
	}

	public function formGroup_end()
	{
		return '</div>
		</div>';
	}

	function input($label = '',$name = '',  $value = '', $help = '', $attrs = array())
	{
		return $this->form_common('text',$name, $label, $value, $help,$attrs);
	}

	function xeditable($label = '',$name = '', $value = '', $help = '', $attrs = array()){

		$default = 
		[
			'data-original-title' => force_var($label, $name),
			'data-pk' => 0,
			'data-type-data'  => 'meta_data',
			'data-name' =>  force_var($name),
			'data-value'  => force_var($value),
			'is_x_editable' => 'true', 
		];

		$attrs = array_merge($default,$attrs);

		return $this->input($label,$name,$value,$help,$attrs);
	}

	function input_numberic($label = '',$name = '',  $value = '', $help = '', $attrs = array()){

		return $this->form_common('number',$name, $label, $value, $help,$attrs);
	}

	private function addon_begin($text = '')
	{
		if($text)
			return '<span class="input-group-addon">'.$text.'</span>';
	}

	private function addon_end($text = '')
	{
		if($text)
			return '<span class="input-group-addon">'.$text.'</span>';
	}

	function form_open($action = '', $attributes = array(), $hidden = array())
	{
		return form_open($action, $attributes , $hidden);
	}

	function open_multipart($action = '', $attributes = array(), $hidden = array())
	{
	}

	function hidden($label = '',$name = '',  $value = '', $help = '', $attrs = array())
	{
		$tmp_col = $this->col;
		$tmp_label_col = $this->label_col;
		//set full
		$html =  $this->set_col(12,0)->form_common('hidden',$name, $label, $value, $help,$attrs);

		//reset current column
		$this->set_col($tmp_col, $tmp_label_col);
		return $html;
	}

	function password($label = '',$name = '',  $value = '', $help = '', $attrs = array())
	{
	}

	function upload($label = '',$name = '',  $value = '', $help = '', $attrs = array()){

		if(empty($attrs['class'])) {

			$attrs['class'] = 'file_loading';
		}

		return $this->form_common('file',$name, $label, $value, $help,$attrs);
	}

	function textarea($label = '',$name = '',  $value = '', $help = '', $attrs = array())
	{
		$attrs['class'] = 'form-control '.@$attrs['class'];
		$attrs['name'] = is_array($name) ? ($name['name'] ?? '') : $name;
		$html = $this->formGroup_begin(0, $label);
		$html.= form_textarea($name, $value, $attrs);
		
		if($help != '')
		{
			$html.= '<p class="help-block">' . $help . '</p>';
		}
		
		$html.=$this->formGroup_end();
		return $html;
	}
	function multiselect($name = '', $options = array(), $selected = array(), $extra = '')
	{
	}

	function dropdown($label = '', $name = '', $options = array(), $selected = array(), $help='', $attrs = array()){

		$defaults = array(
			'id'	=> empty($attrs['id']) ? 'id_'.rand(0,9999) : $attrs['id'],
			'class'	=> 'select2 ' . @$attrs['class'],
			);

		$html = $this->formGroup_begin(0,$label);
		$html.= '<div class="input-group col-sm-12">';
		$html.= $this->addon_begin(@$attrs['addon_begin']);
		$html.= form_dropdown($name , $options, $selected, _parse_form_attributes($attrs, $defaults));
		$html.= $this->addon_end(@$attrs['addon_end']);
		$html.= '</div>';
		if($help != ''){

			$html.= '<p class="help-block">' . $help . '</p>';
		}
		$html.= $this->formGroup_end();
		return $html;
	}

	function checkbox($data = '', $value = '', $checked = FALSE, $extra = ''){

		return form_checkbox($data, $value, $checked, $extra);
	}

	function radio($data = '',$value='', $checked = FALSE, $extra = ''){

		return form_radio($data, $value, $checked, $extra);
	}

	function submit($label = '',$name = '',  $value = FALSE, $help = '', $attrs = array()){

		if($value === FALSE) 
			return $this->_submit($label,$name);

		return $this->form_common('submit',$name, $label, $value, $help, $attrs);
	}


	function _submit($data = '', $value = '', $extra = ''){

		if(!is_array($data)){

			$name = $data;
			$data = array();
			$data['name'] = $name;
			$data['class'] = 'btn btn-info';
		}

		return form_submit($data, $value, $extra);
	}

	function reset($data = '', $value = '', $extra = '')
	{
	}

	function button($label = '',$name = '',  $value = '', $help = '', $attrs = array()){

		return $this->form_common('button',$name, $label, $value, $help,$attrs);
	}

	function label($label_text = '', $id = '', $attributes = array())
	{
	}
	function form_close($extra = '')
	{
		return form_close($extra);
	}

	function box_open($title = '', $class = 'box-theme')
	{
		$this->ci->template->is_box_open->set(1);
		$html= '<div class="col-md-'.$this->col.'">';
		$html.= '<div class="box '.$class.'">';
		if(!empty($title))
		{
			$html.= '<div class="box-header with-border">';
			$html.= '<h3 class="box-title">'.$title.'</h3>';
			$html.= '<div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </button>
                  </div>';
			$html.= '</div>';
		}
		$html.= '<div class="box-body">';
		return $html;
	}

	function box_close($submit = array(), $cancel = array())
	{
		$html = '</div>';
		if(!empty($submit) || !empty($cancel))
			$html.= $this->box_submit($submit, $cancel);
		$html.= '</div>';
		$html.= '</div>';
		return $html;
	}

	function box_submit($submit = array(), $cancel = array())
	{
		$cancel['name'] = $cancel[0];
		$cancel['value'] = $cancel[1];
		unset($cancel[0]);
		unset($cancel[1]);
		$cancel['type'] = 'button';
		$cancel['class'] = 'btn '. (isset($cancel['class']) ? $cancel['class']: 'btn-default');
		$btn_submit = call_user_func_array(array($this, '_submit'), $submit);
		$btn_cancel = call_user_func_array(array($this, 'button'), $cancel);
		$btn_cancel = form_input($cancel);

		return '<div class="box-footer text-center">
		'.$btn_submit.''.$btn_cancel.'
		</div>';
	}

	function set_col($col = 12, $label_col = 2){
		$this->col = $col;
		$this->label_col = $label_col;
		return $this;
	}
	
	function box_alert($message = '',$type='warning',$title = '')
	{
		$alert_types = array(
			'warning'=>array('data'=>'Warning','icon'=>'warning'),
			'info'=>array('data'=>'Info','icon'=>'info'),
			'danger'=>array('data'=>'Danger','icon'=>'ban'),
			'success'=>array('data'=>'Success','icon'=>'check')
		);

		if(!isset($alert_types[$type])){
			reset($alert_types);
			$type = key($alert_types);
		}
		
		$content = '
		<div class="alert alert-'.$type.' alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-'.$alert_types[$type]['icon'].'"></i> '.force_var($title,$alert_types[$type]['data']).'</h4>'.$message.'
		</div>';

		return $content;
	}

	function progress_bar($actual = 0, $total = 0)
	{
		$percent = div($actual,$total)*100;

		if($percent >= 50 && $percent < 80)
			$progress_color = 'progress-bar-yellow';
		else if($percent >= 80 && $percent < 90)
			$progress_color = 'progress-bar-aqua';
		else if($percent >= 90)
			$progress_color = 'progress-bar-green';
		else $progress_color = 'progress-bar-red';
		
		$result = '<div class="progress-group">
			<span class="progress-text" data-toggle="tooltip">'.numberformat($percent).'%</span>
			<span class="progress-number"><b><span data-toggle="tooltip" title="Đã thực hiện">'.numberformat($actual).'</span></b>/<span data-toggle="tooltip" title="Tổng">'.numberformat($total).'</span></span>
			<div class="progress sm">
				<div class="progress-bar '.$progress_color.' progress-bar-striped" style="width: '.$percent.'%"></div>
			</div>
		</div>';

		return $result;
	}
}
/* End of file admin_form.php */
/* Location: ./application/libraries/admin_form.php */