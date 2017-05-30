<?php
class IndexModel extends My_Model{
    public function __construct() {
        parent::__construct();
    }
    
    // List Product
    public function listProduct($position = 0, $length = 5) {
       $query   =   $this->db->select('id, name, image_link, price, warranty, gifts, created')
                             ->from('tbl_default_crud')                             
                             ->order_by('id', 'desc')
                             ->limit($length, $position)
                             ->get();                           
       $result  =   $query->result();
       return $result;
    }
    
    // Count Item
    public function countItems() {
        $result = $this->db->count_all('tbl_default_crud');
        return $result;
    }
	
	// Add
	public function add($arrParams) {
		if(!empty($arrParams)) {
			$arrParams['created']	   = time();
			$arrParams['image_link']   = 'image-not-available.jpg';
			$this->db->insert('tbl_default_crud', $arrParams);
			return $this->db->affected_rows();
		}
	}
	
	// Delete
	public function delete($id, $option = 'single') {
	    if($option == 'single') {
	        $this->db->delete('tbl_default_crud', array('id' => $id));
	        return $this->db->affected_rows();
	    }	    
	}
	
	// Delete Selected
	public function deleteSelected($arrParam) {
	    if(!empty($arrParam)) {
	        $countItemDelete   = 0;
	        foreach($arrParam as $id) {
	           $this->db->delete('tbl_default_crud', array('id' => $id));
	           $countItemDelete++;
	        }
	    }
	    return $countItemDelete;
	}
	
	// View
	public function view($id) {
	    $query   =   $this->db->select('id, name, price, warranty, gifts, created')
                        	  ->from('tbl_default_crud')
                        	  ->where('id', $id)
                        	  ->get();
	    $result  =   $query->row();
	    return $result;
	}
	
	// Update
	public function update($id, $arrParam) {
	    if($this->db->update('tbl_default_crud', $arrParam, 'id = ' . $id) == true) return true;
	    return false;
	}
}