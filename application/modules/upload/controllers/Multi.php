<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Multi extends My_Controller  {   
    public $_upload;
   
   // construct
   public function __construct() {
       parent::__construct();
       $this->load->library('session');
       $this->load->model('UploadModel');
   }
   
   // home
   public function index() {
       $data['_title']          = 'Quản lý hình ảnh :: Đăng 1 ảnh';
       $data['_cssFiles']       = $this->appendCssJs(array('css' => TEMPLATE_URL . '/upload/css/style.css'));
       $data['_jsFiles']        = $this->appendCssJs(array('css' => TEMPLATE_URL . '/upload/multi/js/upload.js'), 'js');
       if(!empty($_FILES)){
            if($this->session->has_userdata('token') == $this->_params['token']){
                $this->session->unset_userdata('token');  
                redirect('multi-upload-ajax.html');
            }
            else {
                $this->session->set_userdata('token', $this->_params['token']);
            }
            $count  = $this->upload();
            $data['count']   =   $count;            
       }
       
       $this->render('multi/index', $data);      
   }
    
   // upload image
   private function upload() {
        $this->load->helper('string');
        $images             =   $_FILES['file-upload'];
        $folderUpload       =   PUBLIC_PATH . 'data/upload/multi/';
        $count              =   count($images['name']);
        foreach($images['name'] as $key => $value) {            
            $pathInfo       =   pathinfo($images['name'][$key], PATHINFO_EXTENSION);
            $fileRename     =   random_string('alnum', 7) . '.' . $pathInfo;
            $fileName       =   str_replace($images['name'][$key], $fileRename , $folderUpload . $images['name'][$key]);
            if(@move_uploaded_file($images['tmp_name'][$key], $fileName) == true) {
               $this->UploadModel->insert($fileRename);
            }
        }
        return $count;
   } 
   
   // list image
   public function listImage() {
       $images      = $this->UploadModel->items();
       $folderURL   = PUBLIC_URL . 'data/upload/multi/';
       $xhtml = '';
       if(!empty($images)) {
           foreach($images as $key => $value) {
               $xhtml .= '<div class="col-md-3">
                               <div class="thumbnail">
                                   <img src="'.$folderURL.$value->name.'" style="width:240px; height:180px">
                                   <div class="caption text-center">
                                       <a href="#" class="label label-success" data-toggle="modal" data-target="#myModal" onclick="javascript:showImage(\''.$folderURL.$value->name.'\')">View</a>
                                       <a href="#" class="label label-success" role="button" onclick="javascript:deletePicture(\''.$value->id.'\')">Delete</a>
                                   </div>
                               </div>
                          </div>';
           }    
       }      
       echo $xhtml; 
   }
   
   // delete image
   public function delete() {
       $folder              = PUBLIC_PATH . 'data/upload/multi/';
       $id                  = (isset($this->_params['image'])) ? $this->_params['image'] : null;     
       $name                = $this->UploadModel->delete($id, 'name');       
       $flag1               = (unlink($folder.$name->name) == true)     ? 'success' : 'error'; 
       $flag2               = ($this->UploadModel->delete($id, 'id') == true) ? 'success' : 'error';
       $status              = array('id' => $flag1, 'name' => $flag2);
       echo json_encode($status);
   }
   
   // show image
   public function showImage() {
       $folder              = PUBLIC_PATH . 'data/upload/multi/';
       $id                  = isset($this->_params['id']) ? $this->_params['id'] : null;
       $name                = $this->UploadModel->view($id);
       $link                = $folder . $name->name;
       $status              = array('status' => $link);
       echo json_encode($status);
   }
}