<?php
class indexModel extends CI_Model
{
    public function getBrandHome()
    {
        $query = $this->db->get_where('brands', ['status' => 1]);
        return $query->result();
    }
    public function getCategoryHome()
    {
        $query = $this->db->get_where('categories', ['status' => 1]);
        return $query->result();
    }

    public function getAllProduct()
    {
        // Chọn tất cả các cột từ bảng products và cột quantity từ bảng warehouses
        $this->db->select('products.*, warehouses.quantity');
        $this->db->from('products');
        $this->db->join('warehouses', 'warehouses.product_id = products.id', 'left');
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getCustomerToken($email)
    {
        $query = $this->db->get_where('users', ['email' => $email]);
        return $query->row();
    }

    public function activeCustomerAndUpdateNewToken($email, $data_customer)
    {
        $this->db->trans_start();
        $this->db->update('users', $data_customer, ['email' => $email]);
        if ($this->db->affected_rows() == 0) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // comment
   public function commentSend($data)
    {
        $this->db->trans_start();
        $this->db->insert('comment', $data);
        if ($this->db->affected_rows() == 0) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_complete();
        return true;
    }
    public function getListConmment()
    {
        $query = $this->db->get_where('comment', ['status' => 1]);
        return $query->result();
    }



    // Pagination
    public function countAllProduct()
    {
        return $this->db->count_all('products');
    }
    public function countAllCategory()
    {
        return $this->db->count_all('categories');
    }
    public function countAllBrand()
    {
        return $this->db->count_all('brands');
    }
    public function countAllUser()
    {
        return $this->db->count_all('users');
    }
    public function countAllProductByCate($id)
    {
        $this->db->where('category_id', $id);
        $this->db->from('products');
        return $this->db->count_all_results();

    }
    public function countAllProductByBrand($id)
    {
        $this->db->where('brand_id', $id);
        $this->db->from('products');
        return $this->db->count_all_results();

    }
    public function countAllProductByKeyword($keyword)
    {
        $this->db->like('products.title', $keyword);
        $this->db->from('products');
        return $this->db->count_all_results();

    }


    public function getIndexPagination($limit, $start)
    {
        $query = $this->db->select('categories.title as tendanhmuc, 
                                    products.*, warehouses.*, 
                                    brands.title as tenthuonghieu')->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('warehouses', 'products.id = warehouses.product_id')
            ->join('brands', 'brands.id = products.brand_id')
            ->limit($limit, $start)
            ->get();
        return $query->result();
    }


    public function getCategoryPagination($id, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->where('products.category_id', $id)
            ->get();
        return $query->result();
    }

    public function getCategoryKyTuPagination($id, $kytu, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->where('products.category_id', $id)
            ->order_by('products.title', $kytu)
            ->get();
        return $query->result();
    }
    public function getCategoryPricePagination($id, $gia, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->where('products.category_id', $id)
            ->order_by('products.selling_price', $gia)
            ->get();
        return $query->result();
    }
    public function getCategoryPriceRangePagination($id, $from_price, $to_price, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->where('products.category_id', $id)
            ->where('products.selling_price >=' . $from_price)
            ->where('products.selling_price <=' . $to_price)
            ->order_by('products.selling_price', 'asc')
            ->get();
        return $query->result();
    }
    public function getBrandPagination($id, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->where('products.brand_id', $id)
            ->get();
        return $query->result();
    }

    public function getCategorySlug($id)
    {
        $this->db->select('categories.*');
        $this->db->from('categories');
        $this->db->limit(1);
        $this->db->where('categories.id', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $title = $result->slug;
    }
    public function getBrandSlug($id)
    {
        $this->db->select('brands.*');
        $this->db->from('brands');
        $this->db->limit(1);
        $this->db->where('brands.id', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $title = $result->slug;
    }
    public function getSearchPagination($keyword, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->like('products.title', $keyword)
            ->get();
        return $query->result();
    }
    // Hết pagination


    public function getItemsCategoryHome()
    {
        $this->db->select('products.*, categories.title as cate_title, categories.id');
        $this->db->from('categories');
        $this->db->join('products', 'products.category_id = categories.id');
        $query = $this->db->get();
        $result = $query->result_array();
        // print_r($result);
        $newArray = array();
        foreach ($result as $key => $value) {
            $newArray[$value['cate_title']][] = $value;
        }
        // print_r($newArray);
        return $newArray;

    }





    public function getCategoryTitle($id)
    {
        $this->db->select('categories.*');
        $this->db->from('categories');
        $this->db->limit(1);
        $this->db->where('categories.id', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $title = $result->title;
    }

    public function getMinPriceProduct($id)
    {
        $this->db->select('MIN(selling_price) AS min_price');
        $this->db->from('products');
        $this->db->where('products.category_id', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->min_price : null;
    }
    
    public function getMaxPriceProduct($id)
    {
        $this->db->select('MAX(selling_price) AS max_price');
        $this->db->from('products');
        $this->db->where('products.category_id', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->max_price : null;
    }
    



    public function getBrandProduct($id)
    {
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->where('products.brand_id', $id)
            ->get();
        return $query->result();
    }

    public function getCategoryProduct($id)
    {
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->where('products.category_id', $id)
            ->get();
        return $query->result();
    }
    public function getProductDetails($id)
    {
        $query = $this->db->select('categories.title as tendanhmuc, 
                                     products.*, brands.title as tenthuonghieu, 
                                     warehouses.quantity')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->join('warehouses', 'warehouses.product_id = products.id', 'left')
            ->where('products.id', $id)
            ->get();
    
        $result = $query->result();
        if (empty($result)) {
            // You can log or handle this case
            log_message('debug', 'No product details found for ID: ' . $id);
        }
        return $result;
    }
    


    public function getBrandTitle($id)
    {
        $this->db->select('brands.*');
        $this->db->from('brands');
        $this->db->limit(1);
        $this->db->where('brands.id', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $title = $result->title;
    }
    public function getProductTitle($id)
    {
        $this->db->select('products.title');
        $this->db->from('products');
        $this->db->limit(1);
        $this->db->where('products.id', $id);
        $query = $this->db->get();
        $result = $query->row();
    
        if ($result) {
            return $result->title;
        } else {
            // Handle the case where the product is not found
            return null; // Or you can return a default value or error message
        }
    }
    

    // Tìm kiếm với từ khóa
    public function getProductByKeyword($keyword)
    {
        $query = $this->db->select('categories.title as tendanhmuc, products.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('brands', 'brands.id = products.brand_id')
            ->like('products.title', $keyword)
            ->get();
        return $query->result();
    }


    public function getProfileUser($user_id)
    {
        $query = $this->db->select('*')
            ->from('customers')
            ->where('id', $user_id)
            ->get();
        return $query->row();
    }


    public function updateCustomer($user_id, $data)
    {
        $this->db->trans_start();
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        if ($this->db->affected_rows() == 0) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_complete();
        return true;
    }


    // Comment
    public function getAllComment()
    {
        $this->db->select('comment.*, products.title AS product_name');
        $this->db->from('comment');
        $this->db->join('products', 'comment.product_id_comment = products.id');
        $this->db->where('products.status', 1);  // Chỉ lấy sản phẩm có status = 1
        $query = $this->db->get();
        return $query->result();
    }

    public function deleteComment($cmt_id)
    {
        $this->db->trans_start();
        $this->db->delete('comment', ['id' => $cmt_id]);
        if ($this->db->affected_rows() == 0) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_complete();
        return true;
    }
    public function selectCommentById($cmt_id)
    {
        $query = $this->db->get_where('comment', ['id' => $cmt_id]);
        return $query->row();
    }
    public function updateComment($cmt_id, $data)
    {
        $this->db->trans_start();
        $this->db->where('id', $cmt_id);
        $this->db->update('comment', $data);
        if ($this->db->affected_rows() == 0) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_complete();
        return true;
    }

}

?>