<?php
class orderModel extends CI_Model
{

    public function newShipping($data)
    {
        $this->db->insert('shipping', $data);
        return $form_of_payment_id = $this->db->insert_id();
    }
    public function insert_order($data_order)
    {
        $this->db->insert('orders', $data_order);
        return $order_id = $this->db->insert_id();
    }

    public function insert_order_detail($data_order_detail)
    {
        $this->db->insert('order_detail', $data_order_detail);
        return $order_detail_id = $this->db->insert_id();
    }

    public function get_qty_product_insert_to_order_batches($product_id, $quantity)
    {

        $sql = "
            WITH BatchPriority AS (
                SELECT 
                    Batch_ID, 
                    ProductID, 
                    Expiry_date, 
                    remaining_quantity,
                    ROW_NUMBER() OVER (ORDER BY Expiry_date ASC) AS RowNum
                FROM batches
                WHERE ProductID = ? AND remaining_quantity > 0
            ),
            SelectedBatches AS (
                SELECT 
                    Batch_ID, 
                    ProductID, 
                    Expiry_date, 
                    remaining_quantity,
                    RowNum,
                    SUM(remaining_quantity) OVER (ORDER BY RowNum) AS AccumulatedQuantity
                FROM BatchPriority
            )
            SELECT 
                Batch_ID, 
                CASE 
                    WHEN AccumulatedQuantity <= ? THEN remaining_quantity
                    ELSE ? - (AccumulatedQuantity - remaining_quantity)
                END AS QuantityToTake
            FROM SelectedBatches
            WHERE AccumulatedQuantity - remaining_quantity < ?
        ";

        $query = $this->db->query($sql, [$product_id, $quantity, $quantity, $quantity]);
        return $query->result_array();
    }




    public function get_qty_product_in_batches($product_id, $quantity)
    {

        if (empty($product_id) || $quantity <= 0) {
            return [];
        }

        $sql = "
        WITH BatchPriority AS (
            SELECT 
                Batch_ID, 
                ProductID, 
                Expiry_date, 
                remaining_quantity,
                ROW_NUMBER() OVER (ORDER BY Expiry_date ASC) AS RowNum
            FROM batches
            WHERE ProductID = ? AND remaining_quantity > 0
        ),
        SelectedBatches AS (
            SELECT 
                Batch_ID, 
                ProductID, 
                Expiry_date, 
                remaining_quantity,
                RowNum,
                SUM(remaining_quantity) OVER (ORDER BY RowNum) AS AccumulatedQuantity
            FROM BatchPriority
        )
        SELECT 
            Batch_ID, 
            CASE 
                WHEN AccumulatedQuantity <= ? THEN remaining_quantity
                ELSE ? - (AccumulatedQuantity - remaining_quantity)
            END AS QuantityToTake
        FROM SelectedBatches
        WHERE AccumulatedQuantity - remaining_quantity < ?
    ";

        $query = $this->db->query($sql, [$product_id, $quantity, $quantity, $quantity]);

        if (!$query) {
            return [];
        }
        $batches = $query->result_array();
        $totalQuantity = 0;
        foreach ($batches as $batch) {
            $totalQuantity += $batch['QuantityToTake'];
        }
        $shortage = max(0, $quantity - $totalQuantity);
        return [
            'batches' => $batches,
            'totalQuantity' => $totalQuantity,
            'shortage' => $shortage
        ];
    }


    public function deductBatchQuantity($batch_id, $quantity_to_deduct)
    {
        $this->db->set('remaining_quantity', 'remaining_quantity - ' . (int) $quantity_to_deduct, false);
        $this->db->where('Batch_ID', $batch_id);
        $this->db->update('batches');

        // if ($this->db->affected_rows() > 0) {
        //     log_message('error', "Batch_ID: $batch_id - Giảm số lượng thành công: $quantity_to_deduct");
        // } else {
        //     log_message('error', "Lỗi: Không thể cập nhật Batch_ID: $batch_id với số lượng $quantity_to_deduct");
        // }
    }


    public function insert_order_batches($data_order_batches)
    {
        return $this->db->insert('order_batches', $data_order_batches);
    }


    // public function selectOrder($limit, $start, $filter = [])
    // {
    //     $this->db->select('orders.*, shipping.*')
    //         ->from('orders')
    //         ->join('shipping', 'orders.ShippingID = shipping.id')
    //         ->order_by('(CASE WHEN orders.Order_Status = -1 THEN 0 ELSE 1 END)', 'ASC')
    //         ->order_by('orders.Order_Status', 'ASC')
    //         ->order_by('orders.Date_Order', 'DESC');

    //     if (!empty($filter['keyword'])) {
    //         $this->db->group_start()
    //             ->like('shipping.Name', $filter['keyword'])
    //             ->or_like('shipping.Phone', $filter['keyword'])
    //             ->or_like('shipping.Email', $filter['keyword'])
    //             ->or_like('shipping.Address', $filter['keyword'])
    //             ->group_end();
    //     }

    //     if ($filter['status'] !== '' && $filter['status'] !== null) {
    //         $this->db->where('orders.Order_Status', $filter['status']);
    //     }

    //     $this->db->limit($limit, $start);

    //     return $this->db->get()->result();
    // }


    // public function countOrder($filter = [])
    // {
    //     $this->db->from('orders')
    //         ->join('shipping', 'orders.ShippingID = shipping.id');

    //     if (!empty($filter['keyword'])) {
    //         $this->db->group_start()
    //             ->like('shipping.Name', $filter['keyword'])
    //             ->or_like('shipping.Phone', $filter['keyword'])
    //             ->or_like('shipping.Email', $filter['keyword'])
    //             ->or_like('shipping.Address', $filter['keyword'])
    //             ->group_end();
    //     }

    //     if ($filter['status'] !== '' && $filter['status'] !== null) {
    //         $this->db->where('orders.Order_Status', $filter['status']);
    //     }

    //     return $this->db->count_all_results();
    // }



    public function selectOrder($limit, $start, $filter = [])
    {
        $this->db->select('orders.*, shipping.*')
            ->from('orders')
            ->join('shipping', 'orders.ShippingID = shipping.id')
            ->order_by('(CASE WHEN orders.Order_Status = -1 THEN 0 ELSE 1 END)', 'ASC')
            ->order_by('orders.Order_Status', 'ASC')
            ->order_by('orders.Date_Order', 'DESC');

        if (!empty($filter['keyword'])) {
            $this->db->group_start()
                ->like('orders.Order_code', $filter['keyword'])
                ->or_like('shipping.Name', $filter['keyword'])
                ->or_like('shipping.Phone', $filter['keyword'])
                ->or_like('shipping.Email', $filter['keyword'])
                ->or_like('shipping.Address', $filter['keyword'])
                ->group_end();
        }

        if ($filter['status'] !== '' && $filter['status'] !== null) {
            $this->db->where('orders.Order_Status', $filter['status']);
        }

        if (!empty($filter['checkout_method'])) {
            $this->db->where('shipping.checkout_method', $filter['checkout_method']);
        }
        if (!empty($filter['date_from'])) {
            $this->db->where('orders.Date_Order >=', $filter['date_from'] . ' 00:00:00');
        }

        if (!empty($filter['date_to'])) {
            $this->db->where('orders.Date_Order <=', $filter['date_to'] . ' 23:59:59');
        }


        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    public function countOrder($filter = [])
    {
        $this->db->from('orders')
            ->join('shipping', 'orders.ShippingID = shipping.id');

        if (!empty($filter['keyword'])) {
            $this->db->group_start()
                ->like('shipping.Name', $filter['keyword'])
                ->or_like('shipping.Phone', $filter['keyword'])
                ->or_like('shipping.Email', $filter['keyword'])
                ->or_like('shipping.Address', $filter['keyword'])
                ->group_end();
        }

        if ($filter['status'] !== '' && $filter['status'] !== null) {
            $this->db->where('orders.Order_Status', $filter['status']);
        }

        if (!empty($filter['checkout_method'])) {
            $this->db->where('shipping.checkout_method', $filter['checkout_method']);
        }
        if (!empty($filter['date_from'])) {
            $this->db->where('orders.Date_Order >=', $filter['date_from'] . ' 00:00:00');
        }

        if (!empty($filter['date_to'])) {
            $this->db->where('orders.Date_Order <=', $filter['date_to'] . ' 23:59:59');
        }


        return $this->db->count_all_results();
    }



    public function getOrderByUserId($user_id)
    {
        $query = $this->db->select('orders.*, order_detail.*, shipping.*')
            ->from('orders')
            ->join('order_detail', 'orders.Order_Code = order_detail.Order_Code', '')
            ->join('shipping', 'orders.ShippingID = shipping.id')
            ->where('shipping.user_id', $user_id)
            ->get();
        return $query->result();
    }




    // public function selectOrderDetails($orderCode)
    // {
    //     $query = $this->db->select('orders.Order_Code, orders.TotalAmount, order_detail.id as order_detail_id,
    //                                 orders.Order_Status as order_status,
    //                                 orders.Payment_Status as payment_status,
    //                                 order_detail.Subtotal as sub, 
    //                                 order_detail.Quantity as qty, 
    //                                 order_detail.Order_Code, 
    //                                 order_detail.ProductID, 
    //                                 product.*, shipping.*')
    //         ->from('order_detail')
    //         ->join('product', 'order_detail.ProductID = product.ProductID', 'left')
    //         ->join('orders', 'orders.Order_Code = order_detail.Order_Code')
    //         ->join('shipping', 'orders.ShippingID = shipping.id')
    //         ->where('order_detail.Order_Code', $orderCode)
    //         ->get();

    //     return $query->result();
    // }


    public function selectOrderDetails($orderCode)
    {
        $query = $this->db->select('
            orders.Order_Code, 
            orders.TotalAmount, 
            orders.DiscountID,
            orders.Order_Status as order_status,
            orders.Payment_Status as payment_status,
            order_detail.id as order_detail_id,
            order_detail.Subtotal as sub, 
            order_detail.Quantity as qty, 
            order_detail.Order_Code, 
            order_detail.ProductID, 
            product.*,
            shipping.*,
            discount.Coupon_code,
            discount.Discount_type,
            discount.Discount_value,
            discount.Min_order_value,
            discount.Max_discount
        ')
            ->from('order_detail')
            ->join('product', 'order_detail.ProductID = product.ProductID', 'left')
            ->join('orders', 'orders.Order_Code = order_detail.Order_Code')
            ->join('shipping', 'orders.ShippingID = shipping.id')
            ->join('discount', 'orders.DiscountID = discount.DiscountID', 'left')
            ->where('order_detail.Order_Code', $orderCode)
            ->get();

        return $query->result();
    }


    public function printOrderDetails($orderCode)
    {
        $query = $this->db->select('orders.Order_Code, order_detail.id as order_detail_id,
                                    orders.Order_Status as order_status,
                                    orders.Payment_Status as payment_status,
                                    order_detail.Subtotal as sub, 
                                    order_detail.Quantity as qty, 
                                    order_detail.Order_Code, 
                                    order_detail.ProductID, 
                                    product.*, shipping.*')
            ->from('order_detail')
            ->join('product', 'order_detail.ProductID = product.ProductID', 'left')
            ->join('orders', 'orders.Order_Code = order_detail.Order_Code')
            ->join('shipping', 'orders.ShippingID = shipping.id')
            ->where('order_detail.Order_Code', $orderCode)
            ->get();

        return $query->result();
    }

    public function deleteOrderBatches($order_detail_id)
    {
        $this->db->where_in('order_detail_id', $order_detail_id);
        return $this->db->delete('order_batches');
    }


    public function deleteOrderDetails($Order_Code)
    {
        $this->db->where_in('Order_Code', $Order_Code);
        return $this->db->delete('order_detail');
    }

    // public function deleteOrder($Order_Code)
    // {
    //     return $this->db->delete('orders', ['Order_Code' => $Order_Code]);
    // }

    public function deleteShipping($ShippingID)
    {
        return $this->db->delete('Shipping', ['id' => $ShippingID]);
    }

    public function deleteOrder($Order_Code)
    {

        $this->db->select('ShippingID');
        $this->db->where('Order_Code', $Order_Code);
        $query = $this->db->get('orders');
        $result = $query->row();

        if ($result) {
            $ShippingID = $result->ShippingID;
            $this->db->delete('orders', ['Order_Code' => $Order_Code]);
            return $ShippingID;
        }
        return false;
    }

    public function updateOrder($data_order, $Order_Code)
    {
        return $this->db->update('orders', $data_order, ['Order_Code' => $Order_Code]);
    }
    public function updateOrderDetails($data_order_details, $Order_Code)
    {
        return $this->db->update('order_detail', $data_order_details, ['Order_Code' => $Order_Code]);
    }

    public function insertRevenue($data)
    {
        $this->db->insert('revenue', $data);
    }
    public function insertWarehouse($data)
    {
        $this->db->insert('revenue', $data);
    }
}
