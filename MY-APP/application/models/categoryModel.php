<?php
class categoryModel extends CI_Model
{
    public function insertCategory($data)
    {
        return $this->db->insert('category', $data);
    }

    public function selectCategory()
    {
        $query = $this->db->get('category');
        return $query->result();
    }
    public function selectCategoryById($CategoryID)
    {
        $query = $this->db->get_where('category', ['CategoryID' => $CategoryID]);
        return $query->row();
    }

    public function updateCategory($CategoryID, $data)
    {
        return $this->db->update('category', $data, ['CategoryID' => $CategoryID]);
    }

    public function checkCategoryInProducts($CategoryID)
    {
        $this->db->select('id');
        $this->db->from('products');
        $this->db->where('category_id', $CategoryID);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            // Nếu có sản phẩm sử dụng danh mục này
            return true;
        } else {
            // Nếu không có sản phẩm nào liên kết
            return false;
        }
    }
    public function deleteCategory($CategoryID)
    {
        return $this->db->delete('category', ['CategoryID' => $CategoryID]);
    }
}

?>