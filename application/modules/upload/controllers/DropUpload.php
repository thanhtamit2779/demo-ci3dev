<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DropUpload extends My_Controller  {   
    public $_upload;
   
   // construct
   public function __construct() {
       parent::__construct();
       $this->load->library('session');
       $this->load->model('DropUploadModel');
   }
   
   // home
   public function index() {
       $data['_title']          = 'Quản lý hình ảnh :: Kéo thả ảnh';
       $data['_jsFiles']        = $this->appendCssJs(array('js' => TEMPLATE_URL . '/upload/drop/js/upload.js'), 'js');     
       $this->render('drop-upload/index', $data);
   }
    
   // upload image
   public function upload() {
        $this->load->helper('string');
        $files              =   isset($_FILES['file']) ? $_FILES['file'] : null;
        $folderUpload       =   PUBLIC_PATH . 'data/upload/drop/';           
        $pathInfo           =   pathinfo($files['name'], PATHINFO_EXTENSION);
        $fileRename         =   random_string('alnum', 7) . '.' . $pathInfo;
        $fileName           =   str_replace($files['name'], $fileRename , $folderUpload . $files['name']);
        if(move_uploaded_file($files['tmp_name'], $fileName) == true) {
           $id  = $this->DropUploadModel->insert($fileRename);
        }
   } 
   
   // list image
   public function listImage() {
       $images      = $this->DropUploadModel->items();
       $folderURL   = PUBLIC_URL . 'data/upload/drop/';
       $xhtml = '';
       if(!empty($images)) {
           foreach($images as $key => $value) {
               $xhtml .= '<div class="col-md-3">
                               <div class="thumbnail">
                                   <img src="'.$folderURL.$value->name.'" style="width:240px; height:180px">
                                   <div class="caption text-center">
                                       <a class="label label-success" data-toggle="modal" data-target="#myModal" onclick="javascript:showImage(\''.$folderURL.$value->name.'\')">View</a>
                                       <a class="label label-success" role="button" onclick="javascript:deletePicture(\''.$value->id.'\')">Delete</a>
                                   </div>
                               </div>
                          </div>';
           }    
       }      
       echo $xhtml; 
   }
   
   // delete image
   public function delete() {
       $folder              = PUBLIC_PATH . 'data/upload/drop/';
       $id                  = (isset($this->_params['image'])) ? $this->_params['image'] : null;     
       $name                = $this->DropUploadModel->delete($id, 'name');       
       $flag1               = (unlink($folder.$name->name) == true)     ? 'success' : 'error'; 
       $flag2               = ($this->DropUploadModel->delete($id, 'id') == true) ? 'success' : 'error';
       $status              = array('id' => $flag1, 'name' => $flag2);
       echo json_encode($status);
   }
   
   // show image
   public function showImage() {
       $folder              = PUBLIC_PATH . 'data/upload/drop/';
       $id                  = isset($this->_params['id']) ? $this->_params['id'] : null;
       $name                = $this->DropUploadModel->view($id);
       $link                = $folder . $name->name;
       $status              = array('status' => $link);
       echo json_encode($status);
   }
}