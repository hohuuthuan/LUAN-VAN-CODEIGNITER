<?php
class warehouseModel extends CI_Model
{
    public function getLatestReceiptNumber() {
        $this->db->select_max('id');
        $query = $this->db->get('warehouse_receipt');
        $result = $query->row_array();
        return isset($result['id']) ? $result['id'] + 1 : 1;
    }
    
    public function insertWarehouseReceiptWithItems($data_warehouse_receipt, $products)
    {
        $this->db->trans_start();
        $this->db->insert('warehouse_receipt', $data_warehouse_receipt);
        $warehouse_receipt_id = $this->db->insert_id();

        if ($warehouse_receipt_id) {
            foreach ($products as $key => $product) {
                $data_warehouse_receipt_items = [
                    'Receipt_id' => $warehouse_receipt_id,
                    'ProductID' => $product['ProductID'] ?? null,
                    'Product_Code' => $product['code'] ?? null,
                    'Unit' => $product['unit'] ?? null,
                    'Import_price' => $product['Import_price'] ?? null,
                    'Exp_date' => $product['Exp_date'] ?? null,
                    'Quantity_doc' => $product['quantity_doc'] ?? null,
                    'Quantity_actual' => $product['quantity_real'] ?? null,
                    'Notes' => $product['note'] ?? null,
                ];
                $this->db->insert('warehouse_receipt_items', $data_warehouse_receipt_items);

                $data_batches = [
                    'Warehouse_Receipt_ID' => $warehouse_receipt_id,
                    'ProductID' => $product['ProductID'] ?? null,
                    'Quantity' => $product['quantity_real'] ?? null,
                    'Import_date' => $data_warehouse_receipt['created_at'] ?? null,
                    'Expiry_date' => $product['Exp_date'] ?? null,
                    'Import_price' => $product['Import_price'] ?? null,
                    'SupplierID' => $data_warehouse_receipt['supplier_id'] ?? null,
                    'remaining_quantity' => $product['quantity_real'] ?? null
                ];
                $this->db->insert('batches', $data_batches);
            }
       
        }
        $this->db->trans_complete(); 
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback(); 
            return false; 
        } else {
            $this->db->trans_commit();
            return $warehouse_receipt_id;
        }
    }





    public function insertWarehouseReceipt($data_warehouse_receipt)
    {
        if ($this->db->insert('warehouse_receipt', $data_warehouse_receipt)) {
            return $this->db->insert_id(); 
        }
        return false;
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

    // public function selectOrder()
    // {
    //     $query = $this->db->select('orders.*, shipping.*')
    //         ->from('orders')
    //         ->join('shipping', 'orders.ShippingID= shipping.id')
    //         ->get();

    //     return $query->result();
    // }

    public function selectOrder()
    {
        $query = $this->db->select('orders.*, shipping.*')
            ->from('orders')
            ->join('shipping', 'orders.ShippingID = shipping.id')
            ->order_by('(CASE WHEN orders.Order_Status = -1 THEN 0 ELSE 1 END)', 'ASC')
            ->order_by('orders.Order_Status', 'ASC')
            ->order_by('orders.Date_Order', 'DESC')
            ->get();

        return $query->result();
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




    public function selectOrderDetails($orderCode)
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

    public function printOrderDetails($orderCode)
    {
        $query = $this->db->select('orders.Order_Code, 
                                    orders.status as order_status, 
                                    order_detail.quantity as qty, 
                                    order_detail.Order_Code,
                                    order_detail.ProductID, product.*')
            ->from('order_detail')
            ->join('product', 'order_detail.ProductID= product.ProductID')
            ->join('orders', 'orders.Order_Code= order_detail.Order_Code')
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

?>