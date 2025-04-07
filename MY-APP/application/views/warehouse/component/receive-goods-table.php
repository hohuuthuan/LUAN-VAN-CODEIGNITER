<table class="table table-striped table-bordered mt20 mb20">
    <thead>
        <tr>
            <th scope="text-center">STT</th>
            <th scope="text-center">Phiếu nhập số</th>
            <th scope="text-center">Ngày tạo</th>
            <th scope="text-center">Họ tên người giao</th>
            <th scope="text-center">Nhà cung cấp</th>
            <th scope="text-center">Số sản phẩm nhập</th> <!-- loại sản phẩm -->
            <th scope="text-center">Tổng số lượng sản phẩm</th>
            <th scope="text-center">Tổng tiền</th>
            <th scope="text-center">Xem chi tiết</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($receive_history as $key => $receive): ?>
            <?php
            $productCount = count($receive['product_items']);
            $totalQuantity = 0;
            foreach ($receive['product_items'] as $item) {
                $totalQuantity += $item['quantity_actual'];
            }
            ?>
            <tr>
                <th scope="row"><?php echo $key + 1; ?></th>
                <td><?php echo $receive['warehouse_receipt_id']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($receive['created_at'])); ?></td>
                <td><?php echo $receive['name_of_delivery_person']; ?></td>
                <td><?php echo $receive['supplier_name']; ?></td>
                <td><?php echo $productCount; ?></td>
                <td><?php echo number_format($totalQuantity); ?></td>
                <td><?php echo number_format($receive['sub_total'], 0, ',', '.'); ?> VNĐ</td>
                <td class="text-center">
                    <a href="<?php echo site_url('warehouse/receive-goods-history/receipt_detail/' . $receive['warehouse_receipt_id']); ?>" title="Xem chi tiết">
                        <i class="fa fa-eye receipt_detail_eye"></i>
                    </a>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>