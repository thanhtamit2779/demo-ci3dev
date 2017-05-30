<?php
class DropUploadModel extends My_Model {
    public function __construct() {
        parent::__construct();
    }
    public function insert($name, $option = null) {
        if(!empty($name)) {
            $created  = time();  
            $query    = "INSERT INTO `tbl_upload_drop`(`name`, `created`) VALUES ('$name', $created)";
            $this->db->query($query);            
        }
        return $this->db->insert_id();
    }
    public function items() {
        $query = $this->db->select('id, name')
                          ->order_by('id', 'desc')
                          ->get('tbl_upload_drop');
        return $query->result();
    }
    public function delete($id, $option = null) {
        $flag       = false;
        if($option == 'id') { // xóa id trong cơ sở dữ liệu
            $this->db->where('id', $id);
            $this->db->delete('tbl_upload_drop');
            $flag       = false;
        }
        if($option == 'name') { // xóa tên ảnh trong thư mục bằng tên ảnh trong cơ sở dữ liệu
            $query = $this->db->select('name')
                              ->where('id', $id)
                              ->get('tbl_upload_drop');
            return $query->row();
        }
        return $flag;
    }
    public function view($id) {
        $query = $this->db->select('name')
                          ->where('id', $id)
                          ->get('tbl_upload_drop');
        return $query->row();
    }
}