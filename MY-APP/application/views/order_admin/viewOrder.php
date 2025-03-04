<?php $this->load->view('admin-layout/component-admin/breadcrumb'); ?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    
                    <div class="row mb15">
                        <div class="col-lg-8">
                            <table class="table table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Product Image</th>
                                        <th scope="col">Order Code</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Product Price</th>
                                        <th scope="col">Sale</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Payment Form</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $previous_order_code = null;
                                    $order_totals = [];
                                    foreach ($order_details as $key => $ord):
                                        // Tính tổng tiền của từng đơn hàng
                                        if (isset($ord->discount) && $ord->discount != 0) {
                                            $discounted_price = $ord->selling_price * (1 - $ord->discount / 100);
                                            $subtotal = $ord->qty * $discounted_price;
                                        } else {
                                            $subtotal = $ord->qty * $ord->selling_price;
                                        }
                                        if (!isset($order_totals[$ord->order_code])) {
                                            $order_totals[$ord->order_code] = 0;
                                        }
                                        $order_totals[$ord->order_code] += $subtotal;
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $key + 1 ?></th>
                                            <td><img src="<?php echo base_url('uploads/product/' . $ord->image) ?>" alt=""
                                                    class="img-fluid" style="max-width: 144px; height: 144px;"></td>
                                            <td><?php echo $ord->order_code ?></td>
                                            <td><?php echo $ord->title ?></td>

                                            <td>
                                                <?php
                                                if (isset($ord->discount) && $ord->discount != 0) {
                                                    $discounted_price = $ord->selling_price * (1 - $ord->discount / 100);
                                                    echo '<del class="text-muted text-decoration-line-through" style="font-size: 1.2rem;">' . number_format($ord->selling_price, 0, ',', '.') . ' VNĐ</del><br>';
                                                    echo '<span class="text-success" style="font-size: 1.4rem; font-weight: bold;">' . number_format($discounted_price, 0, ',', '.') . ' VNĐ</span>';
                                                } else {
                                                    echo '<span class="text-success" style="font-size: 1.4rem; font-weight: bold;">' . number_format($ord->selling_price, 0, ',', '.') . ' VNĐ</span>';

                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $ord->discount ?>%</td>
                                            <td><?php echo $ord->qty ?></td>
                                            <td><?php echo $ord->form_of_payment ?></td>
                                            <td>
                                                <?php
                                                if (isset($ord->discount) && $ord->discount != 0) {
                                                    $discounted_price = $ord->selling_price * (1 - $ord->discount / 100);
                                                    echo '<span class="text-success" style="font-size: 1.4rem; font-weight: bold;">' . number_format($ord->qty * $discounted_price, 0, ',', '.') . ' VNĐ</span>';
                                                } else {
                                                    echo '<span class="text-success" style="font-size: 1.4rem; font-weight: bold;">' . number_format($ord->qty * $ord->selling_price, 0, ',', '.') . ' VNĐ</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                        <div class="col-lg-4">
                            <table class="table table-bordered table-hover">
                                <?php foreach ($order_totals as $order_code => $total): ?>
                                    <thead class="">
                                        <tr>

                                            <th scope="col">Tổng tiền của đơn: <span
                                                    style="color: blue"><?php echo $order_code; ?></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h1 style="font-weight: 900;" class="text-danger">
                                                    <?php echo number_format($total, 0, ',', '.') . ' VNĐ'; ?>
                                                </h1>
                                            </td>
                                        </tr>

                                    </tbody>
                                <?php endforeach; ?>
                            </table>
                            <table class="table table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th scope="col">Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td style="height: 58px;">
                                                <?php if ($ord->order_code != $previous_order_code): ?>
                                                    <select class="form-control form-control-sm order_status setupSelect2"
                                                        data-order-code="<?php echo $ord->order_code ?>">
                                                        <option value="1" <?php echo $ord->order_status == 1 ? 'selected' : '' ?>>
                                                            Xử lý
                                                            đơn
                                                            hàng</option>
                                                        <option value="2" <?php echo $ord->order_status == 2 ? 'selected' : '' ?>>
                                                            Đang
                                                            chuẩn
                                                            bị hàng</option>
                                                        <option value="3" <?php echo $ord->order_status == 3 ? 'selected' : '' ?>>
                                                            Đơn đã
                                                            được
                                                            giao cho đơn vị vận chuyển</option>
                                                        <option value="4" <?php echo $ord->order_status == 4 ? 'selected' : '' ?>>
                                                            Đơn hàng
                                                            đã
                                                            được thanh toán</option>
                                                        <option value="5" <?php echo $ord->order_status == 5 ? 'selected' : '' ?>>
                                                            Hủy
                                                        </option>
                                                    </select>
                                                    <?php
                                                    $previous_order_code = $ord->order_code;
                                                endif;
                                                ?>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
	$(document).on('change', '.order_status', function () {
    const value = $(this).val();
    const order_code = $(this).data('order-code');

    if (value == 0) {
        alert('Hãy chọn trạng thái đơn hàng');
    } else {
        $.ajax({
            url: '/order_admin/update-order-status',
            method: 'POST',
            data: { value: value, order_code: order_code },
            success: function (response) {
                alert('Update trạng thái đơn hàng thành công');
                location.reload(); // Cập nhật lại trang nếu cần
            },
            error: function (xhr, status, error) {
                alert('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng');
            }
        });
    }
});


</script>