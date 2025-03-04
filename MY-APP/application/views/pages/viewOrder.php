<div style="margin-top: -42px;" class="container">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 30px; color: #FE980F;">Chi tiết đơn hàng</h1>
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
        <?php } elseif ($this->session->flashdata('error')) { ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
        <?php } ?>
        <div class="card-body">
            <table class="table">
                <thead style="border-bottom: 3px solid #FE980F;" class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Order Code</th>
                        <th scope="col">Product Image</th>
                        <th scope="col" style="width: 200px;">Product Name</th>
                        <th scope="col">Product Price</th>
                        <th scope="col">Sale</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Hình thức thanh toán</th>
                        <th scope="col">Status</th>
                        <th scope="col">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_sub = 0;
                    foreach ($order_details as $key => $ord_details) {
                        $total_sub += $ord_details->sub;
                        ?>
                        <tr style="border-bottom: 2px solid #FE980F;">
                            <th scope="row"><?php echo $key + 1 ?></th>
                            <td><?php echo $ord_details->order_code ?></td>
                            <td><img src="<?php echo base_url('uploads/product/' . $ord_details->image) ?>" alt="" width="150"
                                    height="150"></td>
                            <td><?php echo $ord_details->title ?></td>
                            <td><?php echo number_format($ord_details->selling_price, 0, ',', '.') ?>vnd</td>
                            <td><?php echo $ord_details->discount ?>%</td>
                            <td><?php echo $ord_details->qty ?></td>
                            <td><?php echo $ord_details->form_of_payment ?></td>
                            <td>
                                <?php
                                if ($ord_details->order_status == 1) {
                                    echo '<span class="text text-primary">Đang chờ xử lý</span>';
                                } elseif ($ord_details->order_status == 2) {
                                    echo '<span class="text text-warning">Người bán đang chuẩn bị hàng</span>';
                                } elseif ($ord_details->order_status == 3) {
                                    echo '<span class="text text-success">Đã giao cho đơn vị vận chuyển, đơn hàng đang được giao đến bạn</span>';
                                } elseif ($ord_details->order_status == 4) {
                                    echo '<span class="text text-success">Đơn hàng đã giao thành công</span>';
                                } else {
                                    echo '<span class="text text-danger">Đã hủy</span>';
                                }
                                ?>
                            </td>
                            <?php if (($ord_details->order_status == 1) || ($ord_details->order_status == 2)) { ?>
                                <td>
                                    <a onclick="return confirm('Bạn chắc chắn xóa đơn này chứ, các sản phẩm trong đơn sẽ được xóa')"
                                        href="<?php echo base_url('order_customer/deleteOrder/' . $ord_details->order_code) ?>"
                                        class="btn btn-danger">Delete Order</a>
                                </td>
                            <?php } elseif ($ord_details->order_status == 3) { ?>
                                <td style="color: #FE980F; width: 150px;">Đơn đang giao đến bạn, không thể hủy đơn</td>
                            <?php } else { ?>
                                <td style="color: green; width: 150px;">Đơn hàng đã giao thành công</td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div style="width: 100%; height:100px; position: relative;">
                <div style="display: flex; position: absolute; right: 0px; top: -30px;">
                    <h3>TỔNG THANH TOÁN:
                        <h3 style="color: #FE980F; margin-left: 10px">
                            <?php echo number_format($total_sub, 0, ',', '.') ?> VNĐ
                        </h3>
                    </h3>
                </div>
            </div>
        </div>
    </div>