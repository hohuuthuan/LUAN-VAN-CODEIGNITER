<?php
class brandModel extends CI_Model
{
    public function insertBrand($data)
    {
        return $this->db->insert('brands', $data);
    }

    public function selectBrand()
    {
        $query = $this->db->get('brands');
        return $query->result();
    }
    public function selectBrandById($id)
    {
        $query = $this->db->get_where('brands', ['id' => $id]);
        return $query->row();
    }

    public function updateBrand($id, $data)
    {
        return $this->db->update('brands', $data, ['id' => $id]);
    }


    public function checkBrandInProducts($brand_id)
    {
        $this->db->select('id');
        $this->db->from('products');
        $this->db->where('brand_id', $brand_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            // Nếu có sản phẩm sử dụng thương hiệu này
            return true;
        } else {
            // Nếu không có sản phẩm nào liên kết
            return false;
        }
    }

    public function deleteBrand($id)
    {
        return $this->db->delete('brands', ['id' => $id]);
    }
}

?>