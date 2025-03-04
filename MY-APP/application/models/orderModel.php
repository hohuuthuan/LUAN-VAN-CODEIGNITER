<?php
class orderModel extends CI_Model
{


    public function selectOrder()
    {
        $query = $this->db->select('orders.*, shipping.*')
            ->from('orders')
            ->join('shipping', 'orders.form_of_payment_id= shipping.id')
            ->get();

        return $query->result();
    }
    public function getOrderByUserId($user_id)
    {
        $query = $this->db->select('orders.*, orders_details.*, shipping.*')
            ->from('orders')
            ->join('orders_details', 'orders.order_code = orders_details.order_code', '')
            ->join('shipping', 'orders.form_of_payment_id = shipping.id')
            ->where('shipping.user_id', $user_id)
            ->get();
        return $query->result();
    }




    public function selectOrderDetails($orderCode)
    {
        $query = $this->db->select('orders.order_code, 
                                    orders.status as order_status,  
                                    orders_details.subtotal as sub, 
                                    orders_details.quantity as qty, 
                                    orders_details.order_code, 
                                    orders_details.product_id, 
                                    products.*, shipping.*')
            ->from('orders_details')
            ->join('products', 'orders_details.product_id = products.id', 'left') // Sử dụng LEFT JOIN để kết hợp với bảng products
            ->join('orders', 'orders.order_code = orders_details.order_code')
            ->join('shipping', 'orders.form_of_payment_id = shipping.id')
            ->where('orders_details.order_code', $orderCode)
            ->get();

        return $query->result();
    }

    public function printOrderDetails($orderCode)
    {
        $query = $this->db->select('orders.order_code, 
                                    orders.status as order_status, 
                                    orders_details.quantity as qty, 
                                    orders_details.order_code,
                                    orders_details.product_id, products.*')
            ->from('orders_details')
            ->join('products', 'orders_details.product_id= products.id')
            ->join('orders', 'orders.order_code= orders_details.order_code')
            ->where('orders_details.order_code', $orderCode)
            ->get();

        return $query->result();
    }

    public function deleteOrder($order_code)
    {
        return $this->db->delete('orders', ['order_code' => $order_code]);
    }



    public function deleteOrderDetails($order_code)
    {
        $this->db->where_in('order_code', $order_code);
        return $this->db->delete('orders_details');
    }

    public function updateOrder($data_order, $order_code)
    {
        return $this->db->update('orders', $data_order, ['order_code' => $order_code]);

    }
    public function updateOrderDetails($data_order_details, $order_code)
    {
        return $this->db->update('orders_details', $data_order_details, ['order_code' => $order_code]);

    }

    public function insertRevenue($data) {
        $this->db->insert('revenue', $data);
    }
    public function insertWarehouse($data) {
        $this->db->insert('revenue', $data);
    }
    
    
}

?>