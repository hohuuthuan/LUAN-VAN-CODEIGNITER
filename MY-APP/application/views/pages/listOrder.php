
<div class="container">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 30px; color: #FE980F;">Danh sách đơn hàng</h1>
        <?php if($this->session->flashdata('success')) { ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
        <?php } elseif($this->session->flashdata('error')) { ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
        <?php } ?>
        <div class="card-body">
            <?php
            $orders_by_code = [];
            foreach ($order_items as $order_item) {
                $orders_by_code[$order_item->order_code][] = $order_item;
            }
            ?>
            <?php foreach ($orders_by_code as $order_code => $order_items_group) { ?>
                <h2>Mã đơn: <?php echo $order_code; ?></h2>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Image</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Status</th>
                            <th scope="col">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_sub = 0;
                        foreach ($order_items_group as $key => $order_item) {
                            $total_sub += $order_item->subtotal;
                        ?>
                            <tr>
                                <th scope="row"><?php echo $key+1; ?></th>
                                <td><img src="<?php echo base_url('uploads/product/'.$order_item->product_details->image) ?>" alt="" width="150" height="150"></td>
                                <td><?php echo $order_item->product_details->title; ?></td>
                                <td><?php echo $order_item->quantity; ?></td>
                                <td style="color: #FE980F;"><?php echo number_format($order_item->subtotal,0, ',','.'); ?> VNĐ</td>
                                <td>
                                    <?php
                                    if ($order_item->status == 1) {
                                        echo '<span class="text text-primary">Đang chờ xử lý</span>';
                                    } elseif ($order_item->status == 2) {
                                        echo '<span class="text text-warning">Người bán đang chuẩn bị hàng</span>';
                                    } elseif ($order_item->status == 3) {
                                        echo '<span class="text text-success">Đã giao cho đơn vị vận chuyển, đơn hàng đang được giao đến bạn</span>';
                                    }
                                    elseif ($order_item->status == 4) {
                                        echo '<span class="text text-success">Đơn hàng đã giao thành công</span>';
                                    } else {
                                        echo '<span class="text text-danger">Đã hủy</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('order_customer/viewOrder/'.$order_item->order_code) ?>" class="btn btn-warning">Xem chi tiết đơn hàng</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div style="width: 100%; height:100px; position: relative; border-bottom: 3px solid #FE980F">                
                    <div style="display: flex; position: absolute; right: 0px; top: -30px;">
                        <h3>TỔNG THANH TOÁN:
                        <h3 style="color: #FE980F; margin-left: 10px">
                            <?php echo number_format($total_sub, 0, ',', '.'); ?> VNĐ
                        </h3></h3>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
