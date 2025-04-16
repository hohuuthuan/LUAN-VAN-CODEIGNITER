<?php
class categoryModel extends CI_Model
{
    public function insertCategory($data)
    {
        return $this->db->insert('category', $data);
    }

    public function countCategory($keyword = null, $status = null)
    {
        if (!empty($keyword)) {
            $this->db->like('Name', $keyword);
        }

        if ($status !== null && $status !== '') {
            $this->db->where('Status', (int)$status);
        }

        return $this->db->count_all_results('category');
    }
    public function selectCategory($keyword = null, $status = null, $limit = 20, $offset = 0)
    {
        if (!empty($keyword)) {
            $this->db->like('Name', $keyword);
        }

        if ($status !== null && $status !== '') {
            $this->db->where('Status', (int)$status);
        }

        $this->db->order_by('Status', 'DESC');
        $this->db->order_by('Name', 'ASC');

        $query = $this->db->get('category', $limit, $offset);
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