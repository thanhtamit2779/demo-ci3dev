<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends My_Controller  {
   // construct
   public function __construct() {
       parent::__construct();
       $this->load->helper('date');
	   $this->load->library('form_validation');
       $this->load->model('crud/IndexModel');
   }
   
   // home
   public function home() {
       // display data
       $data['_title']             = 'Danh Sách Sản Phẩm';                 
	   $data['countItems']		   =  $this->IndexModel->countItems();	   
	   
	   // append css
	   $data['_cssFiles']          =  $this->appendCssJs(array(  PLUGINS_URL . '/datatables/css/dataTables.bootstrap.css', 
	                                                             PLUGINS_URL . '/datatables/css/jquery.dataTables.min.css'	       
	                                                          )
	                                                    );
	   
	   // append js
	   $data['_jsFiles']           =  $this->appendCssJs(array(  PLUGINS_URL . '/datatables/js/dataTables.bootstrap.js',
                                                    	        PLUGINS_URL . '/datatables/js/jquery.dataTables.min.js'
	                                                         )
	                                                   );	   
       $this->render('crud/index/home', $data);
   }
   
   // load more
   public function loadMore() {
       $positionItems              =  $this->_params['position']; // vị trí cần lấy
       $lengthItems                =  3; // số lượng phần tử cần lấy ra
       $data                       =  $this->IndexModel->listProduct($positionItems, $lengthItems);     
       $xhtml                      =  '';
       foreach($data as $value) {
           $linkEdit       = linkCI('default', 'crud', 'index', array('action' => 'edit', 'id' => $value->id));
           $value->gifts   = ($value->gifts == '0') ? '' : $value->gifts;
           $xhtml .= '<tr id="id-'.$value->id.'">
                       <td><input type="checkbox" name="cid[]" value="'.$value->id.'" id="item-'.$value->id.'"/></td>
                       <td class="text-center"><img src="'.DF_CRUD_URL . $value->image_link.'" alt="'.$value->name.'" class="img-rounded img-responsive" height="90" width="120"></td>
                       <td>'.$value->name.'</td>
                       <td>'.number_format($value->price).' đ</td>
                       <td>'.mdate('%d-%m-%Y - %H:%i:%s', $value->created).'</td>
                       <td>'.$value->gifts.'</td>
                       <td>'.$value->warranty.'</td>
                       <td>'.$value->id.'</td>
                       <td>
                           <button class="btn btn-danger btn-xs" onClick="javascript:delete_product('.$value->id.')" type="button"><i class="glyphicon glyphicon-minus"></i></button>
                           <button class="btn btn-success btn-xs" onClick="javascript:edit_product(\''.$linkEdit.'\')" type="button"><i class="glyphicon glyphicon-pencil"></i></button>
                       </td>
                   </tr>';
       }
       echo $xhtml;
   }
   
   // form
   public function form() {                  
        $this->_params['price']     = str_replace(',', '',  $this->_params['price']);
        $this->_params['price']     = str_replace('.', '',  $this->_params['price']);
        
        require_once PUBLIC_PATH . 'libs/Validate.php';
        
        $validate   = new Validate($this->_params);
        
        $validate->addRule('name', 'string', array('min' => 6, 'max' => 255))
                 ->addRule('price', 'int', array('min' => 10000, 'max' => 100000000))
                 ->addRule('warranty', 'string', array('min' => 6, 'max' => 255));        
        $validate->run();
        
        if($validate->isValid() == false){
            $errors     = $validate->showErrorsPublic();
        }else{            
            if($this->_params['act'] == 'add') {
                unset($this->_params['act']);
                unset($this->_params['id']);
                $this->IndexModel->add($this->_params);
            }
			      else {
				        unset($this->_params['act']);
                $this->IndexModel->update($this->_params['id'], $this->_params);
			      }
            $errors     = null;
        }
        
        $status = 'error';
        if(count($errors) == 0) $status = 'success';
        
		$result = array('status' => $status, 'errors' => $errors);
		
		echo json_encode($result);
   }   
   
   // view
   public function edit() {
       $result = $this->IndexModel->view($this->uri->segment(5));
       echo json_encode($result);
   }
   
   // delete
   public function delete() {
       $countDelete   = $this->IndexModel->delete($this->uri->segment(5));
       $result        = array('status' => true, 'total' => $countDelete);
       echo json_encode($result);
   }
   
   // delete select
   public function deleteSelected() {
	   $countItemDeletes = $this->IndexModel->deleteSelected($this->_params['cid']);
	   $result           = array('total' => $countItemDeletes);
	   echo json_encode($result);
   }
}