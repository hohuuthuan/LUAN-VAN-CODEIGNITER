<?php
class indexModel extends CI_Model
{
    // VNPAY
    public function insert_VNPAY($data_vnpay)
    {
        $this->db->trans_start();
        $this->db->insert('vnpay', $data_vnpay);
        if ($this->db->affected_rows() == 0) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_complete();
        return true;
    }

    public function getBrandHome()
    {
        $query = $this->db->get_where('brand', ['Status' => 1]);
        return $query->result();
    }
    public function getCategoryHome()
    {
        $query = $this->db->get_where('category', ['Status' => 1]);
        return $query->result();
    }

    public function getAllProduct()
    {
        $this->db->select('product.*');
        $this->db->from('product');
        $this->db->where('product.Status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllSupplier()
    {
        $this->db->select('suppliers.*');
        $this->db->from('suppliers');
        // $this->db->where('supplier.Status', 1);
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
        return $this->db->count_all('product');
    }
    public function countAllCategory()
    {
        return $this->db->count_all('category');
    }
    public function countAllBrand()
    {
        return $this->db->count_all('brand');
    }
    public function countAllUser()
    {
        return $this->db->count_all('users');
    }
    public function countAllProductByCate($id)
    {
        $this->db->where('CategoryID', $id);
        $this->db->from('product');
        return $this->db->count_all_results();
    }
    public function countAllProductByBrand($id)
    {
        $this->db->where('BrandID', $id);
        $this->db->from('product');
        return $this->db->count_all_results();
    }
    public function countAllProductByKeyword($keyword)
    {
        $this->db->like('product.Name', $keyword);
        $this->db->from('product');
        return $this->db->count_all_results();
    }




    public function getProductPagination($limit, $start)
    {
        $query = $this->db->select('category.Name as tendanhmuc, 
                                    product.*, 
                                    brand.Name as tenthuonghieu, 
                                    COALESCE(SUM(batches.remaining_quantity), 0) as total_remaining')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->join('batches', 'batches.ProductID = product.ProductID', 'left')
            ->group_by('product.ProductID')
            ->order_by('total_remaining', 'DESC')
            ->limit($limit, $start)
            ->get();

        $products = $query->result();

        // Lấy chi tiết số lượng tồn kho theo từng lô
        foreach ($products as $product) {
            $product->batches = $this->get_batches_by_product($product->ProductID);
        }

        return $products;
    }


    public function getIndexPagination($limit, $start)
    {
        $query = $this->db->select('category.Name as tendanhmuc, 
                                    product.*, 
                                    brand.Name as tenthuonghieu, 
                                    COALESCE(SUM(batches.remaining_quantity), 0) as total_remaining')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->join('batches', 'batches.ProductID = product.ProductID', 'left')
            ->where('product.Status', 1)
            ->group_by('product.ProductID')
            ->order_by('total_remaining', 'DESC')
            ->limit($limit, $start)
            ->get();

        $products = $query->result();

        // Lấy chi tiết số lượng tồn kho theo từng lô
        foreach ($products as $product) {
            $product->batches = $this->get_batches_by_product($product->ProductID);
        }

        return $products;
    }



    public function get_batches_by_product($product_id)
    {
        $query = $this->db->select('Batch_ID, Expiry_date, remaining_quantity')
            ->from('batches')
            ->where('ProductID', $product_id)
            ->where('remaining_quantity >', 0)
            ->order_by('Expiry_date', 'ASC')
            ->get();

        return $query->result();
    }





    public function getCategoryPagination($id, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->where('product.CategoryID', $id)
            ->where('product.Status', 1)
            ->get();
        return $query->result();
    }

    public function getCategoryKyTuPagination($id, $kytu, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->where('product.Status', 1)
            ->where('product.CategoryID', $id)
            ->order_by('product.Name', $kytu)
            ->get();
        return $query->result();
    }
    public function getCategoryPricePagination($id, $gia, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->where('product.Status', 1)
            ->where('product.CategoryID', $id)
            ->order_by('product.selling_price', $gia)
            ->get();
        return $query->result();
    }
    public function getCategoryPriceRangePagination($id, $from_price, $to_price, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->where('product.Status', 1)
            ->where('product.CategoryID', $id)
            ->where('product.selling_price >=' . $from_price)
            ->where('product.selling_price <=' . $to_price)
            ->order_by('product.selling_price', 'asc')
            ->get();
        return $query->result();
    }
    public function getBrandPagination($id, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->where('product.Status', 1)
            ->where('product.BrandID', $id)
            ->get();
        return $query->result();
    }

    public function getCategorySlug($CategoryID)
    {
        $this->db->select('category.*');
        $this->db->from('category');
        $this->db->limit(1);
        $this->db->where('category.CategoryID', $CategoryID);
        $query = $this->db->get();
        $result = $query->row();
        return $Slug = $result->Slug;
    }
    public function getBrandSlug($BrandID)
    {
        $this->db->select('brand.*');
        $this->db->from('brand');
        $this->db->limit(1);
        $this->db->where('brand.BrandID', $BrandID);
        $query = $this->db->get();
        $result = $query->row();
        return $Slug = $result->Slug;
    }
    public function getSearchPagination($keyword, $limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->like('product.Name', $keyword)
            ->get();
        return $query->result();
    }



    public function getItemsCategoryHome()
    {
        $this->db->select('product.*, category.Name as cate_name, category.CategoryID');
        $this->db->from('category');
        $this->db->join('product', 'product.CategoryID = category.CategoryID');
        $query = $this->db->get();
        $result = $query->result_array();
        $newArray = array();
        foreach ($result as $key => $value) {
            $newArray[$value['cate_name']][] = $value;
        }
        return $newArray;
    }





    public function getCategoryName($CategoryID)
    {
        $this->db->select('category.*');
        $this->db->from('category');
        $this->db->limit(1);
        $this->db->where('category.CategoryID', $CategoryID);
        $query = $this->db->get();
        $result = $query->row();
        return $Name = $result->Name;
    }

    public function getMinPriceProduct($id)
    {
        $this->db->select('MIN(selling_price) AS min_price');
        $this->db->from('product');
        $this->db->where('product.CategoryID', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->min_price : null;
    }

    public function getMaxPriceProduct($id)
    {
        $this->db->select('MAX(selling_price) AS max_price');
        $this->db->from('product');
        $this->db->where('product.CategoryID', $id);
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->max_price : null;
    }




    public function getBrandProduct($id)
    {
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->where('product.BrandID', $id)
            ->get();
        return $query->result();
    }

    public function getCategoryProduct($CategoryID)
    {
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->where('product.CategoryID', $CategoryID)
            ->get();
        return $query->result();
    }
    // public function getProductDetails($id)
    // {
    //     $query = $this->db->select('category.title as tendanhmuc, 
    //                                  product.*, brand.title as tenthuonghieu, 
    //                                  warehouses.quantity')
    //         ->from('category')
    //         ->join('product', 'product.CategoryID = category.CategoryID')
    //         ->join('brand', 'brand.BrandID = product.BrandID')
    //         ->join('warehouses', 'warehouses.ProductID = product.ProductID', 'left')
    //         ->where('product.ProductID', $id)
    //         ->get();

    //     $result = $query->result();
    //     if (empty($result)) {
    //         // You can log or handle this case
    //         log_message('debug', 'No product details found for ID: ' . $id);
    //     }
    //     return $result;
    // }


    //     public function getProductDetails($ProductID)
    // {
    //     $query = $this->db->select('category.Name as tendanhmuc, 
    //                                  product.*, brand.Name as tenthuonghieu')
    //         ->from('category')
    //         ->join('product', 'product.CategoryID = category.CategoryID')
    //         ->join('brand', 'brand.BrandID = product.BrandID')
    //         ->where('product.ProductID', $ProductID)
    //         ->get();

    //     $result = $query->result();
    //     if (empty($result)) {
    //         // You can log or handle this case
    //         log_message('debug', 'No product details found for ID: ' . $ProductID);
    //     }
    //     return $result;
    // }

    public function getProductDetails($ProductID)
    {
        $query = $this->db->select('category.Name as tendanhmuc, 
                                 product.*, 
                                 brand.Name as tenthuonghieu, 
                                 COALESCE(SUM(batches.remaining_quantity), 0) as total_remaining')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->join('batches', 'batches.ProductID = product.ProductID', 'left')
            ->where('product.ProductID', $ProductID)
            ->group_by('product.ProductID')
            ->get();

        $result = $query->row();
        if (!$result) {
            log_message('debug', 'No product details found for ID: ' . $ProductID);
        }
        return $result;
    }



    public function getBrandName($BrandID)
    {
        $this->db->select('brand.*');
        $this->db->from('brand');
        $this->db->limit(1);
        $this->db->where('brand.BrandID', $BrandID);
        $query = $this->db->get();
        $result = $query->row();
        return $Name = $result->Name;
    }
    public function getProductName($ProductID)
    {
        $this->db->select('product.Name');
        $this->db->from('product');
        $this->db->limit(1);
        $this->db->where('product.ProductID', $ProductID);
        $query = $this->db->get();
        $result = $query->row();

        if ($result) {
            return $result->Name;
        } else {
            // Handle the case where the product is not found
            return null; // Or you can return a default value or error message
        }
    }


    // Tìm kiếm với từ khóa
    public function getProductByKeyword($keyword)
    {
        $query = $this->db->select('category.Name as tendanhmuc, product.*, brand.Name as tenthuonghieu')
            ->from('category')
            ->join('product', 'product.CategoryID = category.CategoryID')
            ->join('brand', 'brand.BrandID = product.BrandID')
            ->like('product.Name', $keyword)
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
    // public function getAllComment()
    // {
    //     $this->db->select('comment.*, product.Name AS product_name');
    //     $this->db->from('comment');
    //     $this->db->join('product', 'comment.ProductID_comment = product.ProductID');
    //     $this->db->where('product.Status', 1);
    //     $query = $this->db->get();
    //     return $query->result();
    // }

    public function getAllComment()
    {
        $this->db->select('comment.*');
        $this->db->from('comment');
        // $this->db->where('comment.status', 1);
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
