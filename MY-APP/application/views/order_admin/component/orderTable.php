<table class="table table-striped table-bordered mt20 mb20">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="checkAll" class="input-checkbox">
            </th>
            <th scope="text-center">#</th>
            <th scope="text-center">Order code</th>
            <th scope="text-center">Customer name</th>
            <th scope="text-center">Customer phone</th>
            <th scope="text-center">Customer address</th>
            <th scope="text-center">Payment method</th>
            <th scope="text-center">Date order</th>
            <th scope="text-center">Status</th>
            <th scope="text-center">Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($order as $key => $ord): ?>
            <tr>
                <td>
                    <input type="checkbox" value="" class="input-checkbox checkBoxItem">
                </td>
                <th scope="row"><?php echo $key + 1; ?></th>
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
                    }elseif ($ord->Order_Status == 1) {
                        echo '<span class="badge badge-warning">Đang chờ xử lý</span>';
                    }elseif ($ord->Order_Status == 2) {
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
                <td style="width: 200px" class="text-center">
                    <a href="<?php echo base_url('order_admin/viewOrder/' . $ord->Order_Code) ?>"
                        class="btn btn-warning btn-sm">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a onclick="return confirm('Bạn chắc chắn muốn xóa chứ?')"
                        href="<?php echo base_url('order_admin/deleteOrder/' . $ord->Order_Code) ?>"
                        class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    <a href="<?php echo base_url('order_admin/printOrder/' . $ord->Order_Code) ?>"
                        class="btn btn-success btn-sm" target="_blank">
                        <i class="fa-solid fa-file-export"></i>
                    </a>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>

</table>