<?php
class brandModel extends CI_Model
{
    public function insertBrand($data)
    {
        return $this->db->insert('brand', $data);
    }

    public function selectBrand()
    {
        $query = $this->db->get('brand');
        return $query->result();
    }
    public function selectBrandById($BrandID)
    {
        $query = $this->db->get_where('brand', ['BrandID' => $BrandID]);
        return $query->row();
    }

    public function updateBrand($BrandID, $data)
    {
        return $this->db->update('brand', $data, ['BrandID' => $BrandID]);
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

    public function deleteBrand($BrandID)
    {
        return $this->db->delete('brand', ['BrandID' => $BrandID]);
    }
}

?>