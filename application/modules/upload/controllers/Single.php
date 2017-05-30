<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Single extends My_Controller  {   
    public $_upload;
   
   // construct
   public function __construct() {
       parent::__construct();
       $this->load->library('session');
   }
   
   // home
   public function index() {
       $data['_title']          = 'Quản lý hình ảnh :: Đăng 1 ảnh';
       $data['_cssFiles']       = $this->appendCssJs(array('css' => TEMPLATE_URL . '/upload/css/style.css'));
       $this->_upload['fileName'] = '';
       if(!empty($_FILES)){
            if($this->session->has_userdata('token') == $this->_params['token']){
                $this->session->unset_userdata('token');  
                redirect('single-upload-ajax.html');
            }
            else {
                $this->session->set_userdata('token', $this->_params['token']);
            }
            $this->upload();
            $this->resize($this->_upload['fileName']);
            $data['message'] = $this->showError();
       }
       $this->render('single/index', $data);      
   }
    
   // upload image
   private function upload() {
       $this->load->helper('string');
       $config['file_name']            = time() . '-' . random_string('alnum', 7);
       $config['upload_path']          = PUBLIC_PATH . 'data/upload/single/';
       $config['allowed_types']        = 'gif|jpg|png|jpeg';
       $this->load->library('upload', $config);       
       if ($this->upload->do_upload('file-upload'))
       {
            $this->_upload['fileName'] = $this->upload->data('file_name');
       }
       else
       {
           $this->_upload['errors']    = $this->upload->display_errors();
       }
       return $this->_upload;
   }
   
   // resize 175 x 125
   private function resize($fileName) {       
       if($fileName != '') {         
           $config['image_library']  = 'gd2';          
           $config['source_image']   = PUBLIC_PATH . 'data/upload/single/' . $fileName;
                    
           $config['create_thumb']   = TRUE;
           $config['maintain_ratio'] = TRUE;
           
           // 175 x 125
           $config['width']          = 175;
           $config['height']         = 125;
           $config['thumb_marker']   = '-' .$config['width'] . 'x' . $config['height'];
           $this->load->library('image_lib', $config);
           $this->image_lib->resize();
           if($this->image_lib->resize() == false) return $this->_upload['errors'] =  $this->image_lib->display_errors();
       }   
   }
   
   // error
   private function showError() {
       if(!empty($this->_upload['errors'])) {
           return $this->_upload['errors'];
       }
       else {
           return 'Upload thành công';
       }
   }
   
   // list image
   public function listImage() {
       $this->load->helper('file');
       $folderName  = PUBLIC_PATH . 'data/upload/single/';
       $images      = get_filenames($folderName);
       rsort($images);
       $xhtml = '';
       if(!empty($images)) {
           foreach($images as $key => $value) {
               $filter = substr($value, 18, 8);
               if($filter == '-175x125') {
                   $folderURL = PUBLIC_URL . 'data/upload/single/';
                   $linkImage = str_replace('-175x125', '', $value);
                   $linkImage = $folderURL . $linkImage;
                   $xhtml .= '<div class="col-md-2">
                                   <div class="thumbnail">
                                       <img src="'.$folderURL.$value.'" >
                                       <div class="caption text-center">
                                           <a href="#" class="label label-success" data-toggle="modal" data-target="#myModal" onclick="javascript:showImage(\''.$linkImage.'\')">View</a>
                                           <a href="#" class="label label-success" role="button" onclick="javascript:deleteImage(\''.$value.'\')">Delete</a>
                                       </div>
                                   </div>
                              </div>';
               }
           }    
       }      
       echo $xhtml; 
   }
   
   // delete image
   public function delete() {
       $folder              = PUBLIC_PATH . 'data/upload/single/';
       $image               = (isset($this->_params['image'])) ? $this->_params['image'] : null;
       $imageRoot           = str_replace('-175x125', '', $image);
       $status['image']     = (unlink($folder.$image) == true)     ? 'success' : 'error';
       $status['imageRoot'] = (unlink($folder.$imageRoot) == true) ? 'success' : 'error';      
       echo json_encode($status);
   }
}