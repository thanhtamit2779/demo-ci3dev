<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_ui{

   private $ci;
   private $table;
   private $distinct;
   private $group_by       = array();
   private $select         = array();
   private $joins          = array();
   private $columns        = array();
   public  $where          = array();
   public  $limit          = array();
   private $or_where       = array();
   private $where_in       = array();
   private $where_not_in   = array();
   private $like           = array();
   private $order_by           = array();
   private $filter         = array();
   private $add_columns    = array();
   private $edit_columns   = array();
   private $unset_columns   = array();
   private $option_columns   = array();
   private $column_callback   = array();
   private $add_search   = array();
   private $paging_config   = array();
   private $table_template   = array();
   private $set_order = TRUE;
   public $last_query = '';

   public function __construct()
   {
      $this->ci =& get_instance();
   }

   private function get_display_result()
   {
      return $this->ci->db->get($this->table);
   }

   public function from($table)
   {
      $this->table = $table;
      return $this;
   }

   public function select($columns, $backtick_protect = TRUE)
   {
      foreach($this->explode(',', $columns) as $val)
      {
         $column = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
         $column = preg_replace('/.*\.(.*)/i', '$1', $column); // get name after `.`
         if(!in_array($column, $this->columns))
            $this->columns[] =  $column;

         $this->select[$column] =  trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));
      }
      $this->ci->db->select($columns, $backtick_protect);
      return $this;
   }

   public function join($table, $fk, $type = NULL)
   {
      $this->joins[] = array($table, $fk, $type);
      // $this->ci->db->join($table, $fk, $type);
      return $this;
   }
   public function or_where($key_condition, $val = NULL, $backtick_protect = TRUE)
   {
      $this->or_where[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->or_where($key_condition, $val, $backtick_protect);
      return $this;
   }
   public function where($key_condition, $val = NULL, $backtick_protect = TRUE)
   {
      $this->where[] = array($key_condition, $val, $backtick_protect);

      //$this->ci->db->where($key_condition, $val, $backtick_protect);
      return $this;
   }
   public function limit($value, $offset = 0)
   {
      $this->limit = array($value, $offset);

      //$this->ci->db->where($key_condition, $val, $backtick_protect);
      return $this;
   }

   public function group_by($val)
   {
      $this->group_by[] = $val;
      //$this->ci->db->group_by($val);
      return $this;
   }
   public function distinct($column)
   {
      $this->distinct = $column;
      $this->ci->db->distinct($column);
      return $this;
   }
   public function like($key_condition, $val = NULL, $backtick_protect = TRUE)
   {
      $this->like[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->like($key_condition, $val, $backtick_protect);
      return $this;
   }
   public function order_by($key_condition, $val = NULL)
   {
      $this->order_by[] = array($key_condition, $val);
      //$this->ci->db->order_by($key_condition, $val);
      return $this;
   }

   public function where_in($key_condition, $val = NULL, $backtick_protect = TRUE)
   {
      $this->where_in[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->where_in($key_condition, $val, $backtick_protect);
      return $this;
   }

   public function where_not_in($key_condition, $val, $backtick_protect = TRUE){

      $this->where_not_in[] = array($key_condition, $val, $backtick_protect);

      $this->ci->db->where_not_in($key_condition, $val, $backtick_protect);

      return $this;
   }

   private function balanceChars($str, $open, $close)
   {
      $openCount = substr_count($str, $open);
      $closeCount = substr_count($str, $close);
      $retval = $openCount - $closeCount;
      return $retval;
   }


   private function explode($delimiter, $str, $open = '(', $close=')')
   {
      $retval = array();
      $hold = array();
      $balance = 0;
      $parts = explode($delimiter, $str);
      foreach($parts as $part)
      {
         $hold[] = $part;
         $balance += $this->balanceChars($part, $open, $close);
         if($balance < 1)
         {
            $retval[] = implode($delimiter, $hold);
            $hold = array();
            $balance = 0;
         }
      }
      if(count($hold) > 0)
      {
         $retval[] = implode($delimiter, $hold);
      }
      return $retval;
   }

   private function get_ordering(){

      return TRUE;
   }

   private function get_filtering(){

      if(!empty($this->filter)) foreach ($this->filter as $key => $value) {

         if(empty($value)){

            $this->ci->db->where($key);

            continue;
         }

         $this->ci->db->like($key, $value);
      }

      return $this->ci->db;
   }

   public function add_filter($key, $value){

      $this->filter[$key] = $value;
   }

   private function get_paging(){

      $page = $this->paging_config['cur_page'];

      $page = (isset($page) && $page >0) ? $page : 1;

      $per_page = (isset($this->paging_config['per_page']) ? $this->paging_config['per_page'] : 1);

      $iStart = ($page - 1) * $per_page;

      $iLength = $per_page;

      if($iLength != '' && $iLength > 0)
         $this->ci->db->limit($iLength, ($iStart)? $iStart : 0);
   }

   private function exec_replace($custom_val, $row_data){

      $replace_string = '';

      if(isset($custom_val['replacement']) && is_array($custom_val['replacement'])){

         //Added this line because when the replacement has over 10 elements replaced the variable "$1" first by the "$10"
         $custom_val['replacement'] = array_reverse($custom_val['replacement'], true);

         foreach($custom_val['replacement'] as $key => $val){

            $sval = preg_replace("/(?<!\w)([\'\"])(.*)\\1(?!\w)/i", '$2', trim($val));

            if(preg_match('/(\w+::\w+|\w+)\((.*)\)/i', $val, $matches) && is_callable($matches[1])){

               $func = $matches[1];

               $args = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[,]+/", $matches[2], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

               foreach($args as $args_key => $args_val){

                  $args_val = preg_replace("/(?<!\w)([\'\"])(.*)\\1(?!\w)/i", '$2', trim($args_val));

                  $args[$args_key] = (in_array($args_val, $this->columns))? ($row_data[($this->check_cType())? $args_val : array_search($args_val, $this->columns)]) : $args_val;
               }

               $replace_string = call_user_func_array($func, $args);
            }
            elseif(in_array($sval, $this->columns)){

               $replace_string = $row_data[($this->check_cType())? $sval : array_search($sval, $this->columns)];
            }
            else{

               $replace_string = $sval;
            }

            $custom_val['content'] = str_ireplace('$' . ($key + 1), $replace_string, $custom_val['content']);
         }
      }
      return $custom_val['content'];
   }


   private function check_cType(){

      $column = $this->ci->input->post('columns');

      if(is_numeric($column[0]['data'])) return FALSE;
      else return TRUE;
   }

   public function add_column($column, $options = array(), $content = NULL, $match_replacement = NULL, $row_attrs =array()){

      if(is_array($column)){

         $options = array_merge($column, $options);

         return $this->add_column($column['data'], $options, $content, $match_replacement, $row_attrs);
      }

      if(!is_array($options)){

         $title = $options;

         $options = array();

         $options['title'] = $title;
      }

      $column_v = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $column));

      $column_v = preg_replace('/.*\.(.*)/i', '$1', $column_v); // get name after `.`

      if(!empty($this->unset_columns) && (in_array($column_v,$this->unset_columns) || $column_v == 'action')) {
         return $this;
      }

      $options['data'] = $column_v;

      $options['row_attrs'] = $row_attrs;

      $this->columns[] =  $column_v;

      if(!isset($options['set_select'])  || $options['set_select'] != FALSE){

         // $this->select[$column_v] =  trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $column));
         $this->select[$column_v] =  trim($column);

      }

      unset($options['set_select']);

      if(!isset($options['set_order'])  || $options['set_order'] != FALSE){

         $options['row_attrs']['order'] = TRUE;
      }

      unset($options['set_order']);

      if($content !== NULL){

         $this->add_columns[$column] = array('content' => $content, 'replacement' => $this->explode(',', $match_replacement));
      }

      $this->option_columns[] = $options;

      return $this;
   }

   public function get_query($filtering = FALSE){

      if($filtering){

         $this->get_filtering();
      }

      if(!empty($this->joins)){

         $this->joins = arrayUnique($this->joins);
         
         foreach($this->joins as $val){

            $this->ci->db->join($val[0], $val[1], $val[2]);
         }
      }

      foreach($this->where as $val){

         $this->ci->db->where($val[0], $val[1], $val[2]);
      }

      foreach($this->or_where as $val){

         $this->ci->db->or_where($val[0], $val[1], $val[2]);
      }

      foreach($this->where_in as $val){

         $this->ci->db->where_in($val[0], $val[1], $val[2]);
      }

      foreach($this->group_by as $val){

         $this->ci->db->group_by($val);
      }

      foreach($this->like as $val){

         $this->ci->db->like($val[0], $val[1], $val[2]);
      }

      if(!empty($this->limit))
      {
         $this->ci->db->limit($this->limit[0], $this->limit[1]);
      }

      if(!empty($this->order_by)){

         $this->order_by = @array_unique($this->order_by);

         if(!empty($this->joins)){

            if(!empty($this->order_by)){

               foreach ($this->order_by as &$val) {

                  if(FALSE === strpos($val[0], '.')){

                     $val[0] = $this->table . '.' . $val[0];
                  }  
               }
            }
         }

         foreach($this->order_by as $val){

            $this->ci->db->order_by($val[0], $val[1]);
         }
      }


      if(strlen($this->distinct) > 0){

         $this->ci->db->distinct($this->distinct);
         $this->ci->db->select($this->columns);
      }

      $result = $this->ci->db->get($this->table, NULL, NULL, FALSE);
      $this->last_query = $this->ci->db->last_query();

      return $result;
   }

   private function get_total_results($filtering = FALSE){

      return $this->get_query($filtering)->num_rows();
   }

   public function produce_output($results){

      $this->ci->load->library('table');

      if($this->table_template) {

         $this->ci->table->set_template($this->table_template);
      }  

      $aaData = array();

      $heading = array();

      $heading['title'] = array();

      $heading['search'] = array();

      $columns = array();

      $array_keys = array();

      foreach ($this->add_search as $key => $value) {

         if(strpos($key, '.')) {

            $array_keys[substr($key, strpos($key,'.') + 1)] = $key;
         }
         else{

            $array_keys[$key] = $key;
         }
      }

      $get_order_by = $this->ci->input->get('order_by');

      $field_order = FALSE;

      $order_by = '';

      if($get_order_by){

         $field_order = key($get_order_by);

         $order_by = end($get_order_by);
      }
      else{

         $field_order = $this->set_default_order_by();

         if(!empty($field_order)){

            $order_by = end($field_order);

            $field_order = reset($field_order);
         }
      }

      foreach($this->option_columns as $r){

         $_title = $r['title'];

         $val_index = isset($array_keys[$r['data']]) ? str_replace('.', '-', $array_keys[$r['data']]) : $r['data'];

         if(!empty($r['row_attrs']['order'])){

            $attrs = array(
               'name'   => "order_by[{$val_index}]",
               'type'   => 'submit',
               'class'  => 'button-simulate',
               'value'  => 'desc',
               );

            $_icon_sort = '';

            if($this->set_order){

               $_icon_sort = '<i class="fa fa-fw fa-sort pull-right"></i>';

               if(strpos($field_order, $r['data']) !== FALSE){

                  $_icon_sort = '<i class="fa fa-fw fa-sort-' . $order_by . ' pull-right"></i>';

                  $attrs['value'] = $order_by == 'asc' ? 'desc' : 'asc';
               }
            }
            
            $_title = form_button($attrs, $r['title'] . $_icon_sort);
         }

         $heading['title'][] = $_title;

         $columns[$r['data']] = $r;

         if(empty($array_keys)) continue;

         if(!in_array($r['data'], array_keys($array_keys))){

            // $heading['search'][] = form_input(array('class'=>'form-control','disabled'=>''));
            $heading['search'][] = '';

            continue;
         }

         if(!empty($this->add_search[$r['data']]['content'])){

            $heading['search'][] = $this->add_search[$r['data']]['content'];

            continue;
         }

         $value = $this->ci->input->get("where[$val_index]") ? $this->ci->input->get("where[$val_index]") : '';

         $placeholder_text = 'Lọc theo '.strip_tags($r['title']);

         $primary_key = $this->ci->{$this->table . '_m'}->primary_key ?? '';

         if(strpos($primary_key, $r['data']) !== FALSE){

            $heading['search'][] = form_input(array('class'=>'form-control td-primary-search','placeholder'=>$placeholder_text,'name'=>"where[{$val_index}]"),$value);

            continue;
         }

         $heading['search'][] = form_input(array('class'=>'form-control','placeholder'=>$placeholder_text,'name'=>"where[{$val_index}]"),$value);
      }
      
      $this->ci->table->set_heading($heading['title']);

      $this->ci->table->add_row($heading['search']);

      if($this->columns){

         if($results){

            foreach($results as $key_r =>$result){

               $row = array();

               foreach($this->columns as $column){

                  if(isset($result[$column])){

                     $row[$column] = $result[$column];
                  }
                  else{

                     $row[$column] = '';
                  }

                  if(isset($this->add_columns[$column])){

                     $row[$column] = $this->exec_replace($this->add_columns[$column],$result);
                  }
               }

               //callback
               foreach($row as $r_key => $r_val)
               {
                  if(isset($this->column_callback[$r_key])){

                     foreach($this->column_callback[$r_key] as $cb_key =>$cb_val){

                        if($cb_val['row_data'] === TRUE){

                           $r = $row[$r_key] = call_user_func_array($cb_val['function'], array($row[$r_key], $r_key, $cb_val['args']));

                        }
                        else{

                           $r = $row = call_user_func_array($cb_val['function'], array($row, $r_key, $cb_val['args']));
                        }

                        //if result == false then no process callback
                        if($r === false)
                           break 2;
                     }
                  }
               }

               //remove row if not is array
              if(!is_array($row)) 
                  continue;

               //remove columns not add
               foreach($row as $r_key => $r_val){

                  if(!isset($columns[$r_key])){

                     unset($row[$r_key]);
                  }
                  else{

                     if(isset($columns[$r_key]['row_attrs']) && $columns[$r_key]['row_attrs']){

                        $columns[$r_key]['row_attrs']['data'] = $r_val;

                        $r_val = $columns[$r_key]['row_attrs'];
                     }

                     $row[$r_key] = $r_val;
                  }
               }

               $this->ci->table->add_row($row);
            }

            // prd(!empty($heading['search']) - count($this->ci->table->rows));
            // if((empty($heading['search']) && count($this->ci->table->rows) == 0) || (!empty($heading['search']) && count($this->ci->table->rows) == 1)))
            if((!empty($heading['search']) - count($this->ci->table->rows)) == 0)
            {
               $this->ci->table->add_row(array('data'=>'Không có dữ liệu', 'colspan'=> count($this->columns), 'class'=>'text-center'));
            }

            // if(empty($heading['search']))
            // {
            //    if(count($this->ci->table->rows) == 0)
            //       $this->ci->table->add_row(array('data'=>'Không có dữ liệu', 'colspan'=> count($this->columns), 'class'=>'text-center'));
            // }
            // else
            // {
            //    if(count($this->ci->table->rows) == 1)
            //       $this->ci->table->add_row(array('data'=>'Không có dữ liệu', 'colspan'=> count($this->columns), 'class'=>'text-center'));
            // }

         }
         else{

            $this->ci->table->add_row(array('data'=>'Không có dữ liệu', 'colspan'=> count($this->columns), 'class'=>'text-center'));
         }
      }

      return $this->ci->table->generate();
   }

   private function set_default_order_by(){

      if($this->set_order){

         $get_order_by = $this->ci->input->get('order_by');

         if(empty($get_order_by) && !empty($this->columns)){

            $primary_key = @$this->ci->{$this->table.'_m'}->primary_key;

            $primary_key = empty($primary_key) ? @$this->ci->{substr($this->table,0,-1).'_m'}->primary_key : $primary_key;

            if(empty($primary_key)){

               $primary_key = $this->ci->scache->get('primary_key-'.$this->table);

               if(empty($primary_key)){

                  $fields = $this->ci->db->field_data($this->table);

                  foreach ($fields as $field){

                     if(!empty($field->primary_key)){

                        $primary_key = $field->name;

                        $this->ci->scache->write($primary_key, 'primary_key-'.$this->table);

                        break;
                     }
                  }
                  
               }
            }

            $field = $this->table.'.'.$primary_key;

            $this->order_by($field, 'desc');
            return array($field, 'desc');
         }
      }
      return false;
   }

   public function parse_relations_searches($args = array(),$replace = '.', $search = '-'){

      if(empty($args) || !is_array($args)) return FALSE;

      foreach($args as $condition => $params){

         if(!empty($params) && is_array($params)) foreach ($params as $field => $value) {

            if(!strpos($field, '-')) continue;

            $args[$condition][str_replace('-', '.', $field)] = $value;

            unset($args[$condition][$field]);
         }
      }

      return $args;
   }

   function generate_pagination($custom_config = array()){

      $this->ci->load->library('pagination');

      $total = $this->get_total_results(TRUE);

      $config = array();

      //auto detect base_url
      $this->ci->load->config('pagination');
      $prefix = $this->ci->config->item('prefix');
      $suffix = $this->ci->config->item('suffix');
      $config['base_url'] = preg_replace('/'.$prefix.'(\d+)'.$suffix.'/i','',  current_url());
      $config['base_url'] = preg_replace('/'.$suffix.'/i','', $config['base_url']);
      $config['base_url'] = rtrim($config['base_url'], '/').'/';
      $segs = $this->ci->uri->segment_array();

      $cur_page = preg_match('/'.$prefix.'(\d+)'.$suffix.'/i', current_url(), $matches) ? $matches[1] : '';

      if($this->ci->router->fetch_method() == 'index')
      {
         $tmp_segs = $segs;
            //remove page number
         array_pop($tmp_segs);
         if(end($segs) != 'index' && array_pop($tmp_segs) !='index')
         {
            $config['base_url'] = $config['base_url'].'index/';
         }
      }

      if(!$cur_page)
      {
         $config['uri_segment'] = count($segs) +1;
      }

      $config['total_rows'] = $total;

      // $config['reuse_query_string'] = TRUE;

      $config['per_page'] = $this->ci->pagination->per_page;

      if(!empty($custom_config)){

         $config = array_merge($config,$custom_config);
      }

      $this->paging_config = $config;
      $this->ci->pagination->initialize($config);
      $links = $this->ci->pagination->create_links();

      if(empty($this->ci->pagination->cur_page))
         $this->pagination_desc = '<span style="font-weight: bold;font-style: italic;float: right;">Hiển thị 1 đến '.$config['total_rows'].' trong tổng số '.$config['total_rows'].' kết quả</span>'.$links;
      else{
         $cur_page = ($this->ci->pagination->cur_page - 1 )  * $config['per_page'];
         $max_page = ($cur_page + $config['per_page']);
         $max_page = $max_page > $config['total_rows'] ? $config['total_rows'] : $max_page;
         $this->pagination_desc = '<span style="font-weight: bold;font-style: italic;float: right;">Hiển thị '.$cur_page.' đến '.$max_page.' trong tổng số '.$config['total_rows'].' kết quả</span>';
      }

      $this->paging_config['cur_page'] = $this->ci->pagination->cur_page;

      return $links;
   }

   public function add_search($column, $options = array(), $content = NULL, $match_replacement = NULL){

      if(is_array($column)){

         $options = array_merge($column, $options);

         return $this->add_search($column['data'], $options, $content, $match_replacement);
      }

      if(!is_array($options)){

         $title = $options;

         $options = array();

         $options['title'] = $title;
      }

      $this->add_search[$column] = $options;

      return $this;
   }


   public function generate($config_pagination = array(), $output = 'json', $charset = 'UTF-8'){

      $result = array();

      $result['pagination'] = $this->generate_pagination($config_pagination);

      $result['table'] = 
      $this->ci->admin_form->form_open('',['method'=>'get','id'=>'datatable-frm']) 
      .form_hidden('search', '1')
      .@$this->pagination_desc
      .$this->generate_data()
      .$this->ci->admin_form->submit('','submit','submit','',array('style'=>'display:none'))
      .$this->ci->admin_form->form_close();
      return $result;
   }

   public function generate_data(){

      $this->get_paging();

      $this->get_ordering();

      $this->get_filtering();

      $this->set_default_order_by();

      $this->select(implode(',',$this->select));

      $result = $this->get_query(TRUE);

      $results = $result->result_array();

      return $this->produce_output($results);
   }

   public function get_columns(){

      return $this->columns;
   }

   public function get_option_columns(){

      return $this->option_columns;
   }

   public function add_column_callback($column, $function, $row_data = TRUE, $args = array()){

      if (is_callable($function)){

         if(is_array($column)) {

            foreach($column as $c) {

               $this->add_column_callback($c,$function, $row_data);
            }
         }
         else{

            $this->column_callback[$column][] = array('function'=>$function, 'row_data' =>$row_data, 'args' =>$args);
         }
      }

      return $this;
   }

   function set_table_template($template){

      $this->table_template = $template;

      return $this;
   }

   function set_ordering($condition = FALSE){
      $this->set_order = is_bool($condition) ? $condition : TRUE;
      return $this;
   }

   /**
    * Unset column
    *
    * @param string $column
    * @return mixed
    */
   public function unset_column($column)
   {
      $this->unset_columns[] = $column;
      return $this;
   }
}