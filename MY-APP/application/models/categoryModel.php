<?php 
    class categoryModel extends CI_Model
    {
        public function insertCategory($data)
        {
            return $this->db->insert('categories', $data);   
        }

        public function selectCategory()
        {
            $query = $this->db->get('categories'); 
            return $query->result();  
        }
        public function selectCategoryById($id)
        {
            $query = $this->db->get_where('categories', ['id' => $id]); 
            return $query->row();  
        }

        public function updateCategory($id, $data)
        {
            return $this->db->update('categories',$data, ['id'=>$id]);   
        }

        public function checkCategoryInProducts($category_id)
    {
        $this->db->select('id');
        $this->db->from('products');
        $this->db->where('category_id', $category_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            // Nếu có sản phẩm sử dụng danh mục này
            return true;
        } else {
            // Nếu không có sản phẩm nào liên kết
            return false;
        }
    }
        public function deleteCategory($id)
        {
            return $this->db->delete('categories',['id'=>$id]);   
        }
    }

?>