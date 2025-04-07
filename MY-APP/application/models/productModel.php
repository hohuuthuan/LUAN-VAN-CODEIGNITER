<?php
class productModel extends CI_Model
{

    public function insertProduct($data)
    {
        $this->db->trans_start();
        $this->db->insert('product', $data);
        if ($this->db->affected_rows() == 0) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_complete();
        return true;
    }

    public function selectAllProduct()
    {
        $query = $this->db->select('categories.title as tendanhmuc, products.*, warehouses.*, brands.title as tenthuonghieu')
            ->from('categories')
            ->join('products', 'products.category_id = categories.id')
            ->join('warehouses', 'products.id = warehouses.product_id')
            ->join('brands', 'brands.id = products.brand_id')
            ->get();
        return $query->result();
    }
    public function selectProductById($ProductID)
    {
        $this->db->select('product.*');
        $this->db->from('product');
        $this->db->where('product.ProductID', $ProductID);
        $query = $this->db->get();
        return $query->row();
    }




    public function updateProduct($ProductID, $data)
    {
        return $this->db->update('product', $data, ['ProductID' => $ProductID]);
    }





    public function getOrdersByProductId($product_id)
    {
        $this->db->select('order_code');
        $this->db->from('orders_details');
        $this->db->where('product_id', $product_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array(); // Trả về danh sách các order_code
        }
        return [];
    }



    public function deleteProduct($id)
    {
        // Lấy danh sách các order code liên quan đến sản phẩm
        $orders = $this->getOrdersByProductId($id);

        if (!empty($orders)) {
            $order_codes = array_column($orders, 'order_code');
            return [
                'status' => false,
                'message' => 'Không thể xóa sản phẩm vì đang được sử dụng trong các đơn hàng: ' . implode(', ', $order_codes)
            ];
        }

        // Xóa các bản ghi liên quan trong bảng warehouses
        $this->db->delete('warehouses', ['product_id' => $id]);

        // Sau đó, xóa sản phẩm trong bảng products
        $this->db->delete('products', ['id' => $id]);

        return ['status' => true, 'message' => 'Đã xóa sản phẩm thành công.'];
    }



    public function plusQuantityProduct($id, $data)
    {
        // Lấy số lượng hiện tại của sản phẩm
        $this->db->select('quantity');
        $this->db->from('warehouses');
        $this->db->where('product_id', $id);
        $query = $this->db->get();
        $result = $query->row();

        if ($result) {
            // Cập nhật số lượng mới bằng cách cộng số lượng hiện tại với số lượng mới
            $new_quantity = $result->quantity + $data['quantity'];
            $new_import_price_one_product = $data['import_price_one_product'];
            // Cập nhật số lượng trong bảng warehouses
            $this->db->set('quantity', $new_quantity);
            $this->db->set('import_price_one_product', $new_import_price_one_product);
            $this->db->where('product_id', $id);
            return $this->db->update('warehouses'); // Trả về true nếu cập nhật thành công
        } else {
            return false; // Trả về false nếu không tìm thấy sản phẩm
        }
    }
    public function plusTotalPriceProduct($id, $total_import_price)
    {
        // Lấy tổng giá hiện tại của sản phẩm
        $this->db->select('total_import_price');
        $this->db->from('warehouses');
        $this->db->where('product_id', $id);
        $query = $this->db->get();
        $result = $query->row();

        if ($result) {
            // Cập nhật tổng giá mới bằng cách cộng tổng giá với giá mới được tính vào
            $new_total_import_price = $result->total_import_price + $total_import_price;

            // Cập nhật tổng giá trong bảng warehouses
            $this->db->set('total_import_price', $new_total_import_price);
            $this->db->where('product_id', $id);
            return $this->db->update('warehouses'); // Trả về true nếu cập nhật thành công
        } else {
            return false; // Trả về false nếu không tìm thấy sản phẩm
        }
    }




    public function getProductsByDisease($disease_name)
    {
        // Tìm sản phẩm liên quan đến tên bệnh
        // $this->db->like('description', $disease_name);
        $this->db->like('description', $disease_name);
        $this->db->where('status', 1);
        $query = $this->db->get('products');

        return $query->result();
    }



    public function getProductsPagination($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get_where('products', ['status' => 1]);
        return $query->result();
    }

    public function countAllProduct()
    {
        return $this->db->where(['status' => 1])->count_all_results('products');
    }




}


?>