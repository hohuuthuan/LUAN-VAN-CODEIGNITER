<table class="table table-striped table-bordered table-hover mt20 mb20">
    <thead>
        <tr>
            <th>#</th>
            <th>Order code</th>
            <th>Customer name</th>
            <th>Customer phone</th>
            <th>Customer address</th>
            <th class="payment-method">Payment method</th>
            <th>Date order</th>
            <th>Status</th>
            <th class="action-order">Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php if (isset($order) && $order != null && count($order) > 0): ?>
            <?php foreach ($order as $key => $ord): ?>
                <tr>
                    <th scope="row"><?php echo ($start + $key + 1); ?></th>
                    <td><?php echo $ord->Order_Code ?></td>
                    <td><?php echo $ord->name ?></td>
                    <td><?php echo $ord->phone ?></td>
                    <td style="width: 300px;"><?php echo $ord->address ?></td>
                    <td><?php echo $ord->checkout_method ?></td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($ord->Date_Order)); ?></td>
                    <td>
                        <?php
                        if ($ord->Order_Status == -1) {
                            echo '<span  class="badge badge-primary">Đơn hàng mới</span>';
                        } elseif ($ord->Order_Status == 1) {
                            echo '<span class="badge badge-warning">Đang chờ xử lý</span>';
                        } elseif ($ord->Order_Status == 2) {
                            echo '<span class="badge badge-warning">Đang chuẩn bị hàng</span>';
                        } elseif ($ord->Order_Status == 3) {
                            echo '<span class="badge badge-success">Đã giao cho đơn vị vận chuyển</span>';
                        } elseif ($ord->Order_Status == 4) {
                            echo '<span class="badge badge-info">Đơn hàng đã được thanh toán</span>';
                        } else {
                            echo '<span class="badge badge-danger">Đã hủy</span>';
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <a href="<?php echo base_url('order_admin/viewOrder/' . $ord->Order_Code) ?>"
                            class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <!-- <a onclick="return confirm('Bạn chắc chắn muốn xóa chứ?')"
                            href="<?php echo base_url('order_admin/deleteOrder/' . $ord->Order_Code) ?>"
                            class="btn btn-danger"><i class="fa fa-trash"></i></a> -->
                        <a href="<?php echo base_url('order_admin/printOrder/' . $ord->Order_Code) ?>"
                            class="btn btn-success btn-sm" target="_blank">
                            <i class="fa-solid fa-file-export"></i>
                        </a>
                    </td>

                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" class="text-center text-danger">
                    <h3>Không có đơn hàng nào</h3>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="text-center">
    <?php echo $links; ?>
</div>