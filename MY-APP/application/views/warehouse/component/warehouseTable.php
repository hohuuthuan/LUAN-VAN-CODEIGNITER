<table class="table table-striped table-bordered mt20 mb20">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="checkAll" class="input-checkbox">
            </th>
            <th scope="text-center">STT</th>
            <th scope="text-center">Tên sản phẩm</th>
            <th scope="text-center">Tồn kho</th>
            <th scope="text-center">Giá giá nhập</th>
            <th scope="text-center">Giá bán</th>
            <th scope="text-center">Đơn vị tính</th>
            <th scope="text-center">Ngày hết hạn</th>
            <th scope="text-center">Trạng thái</th>
            <th scope="text-center">Quản lý</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($products as $key => $pro): ?>
            <tr>
                <td>
                    <input type="checkbox" value="{{ $userCatalogue->id }}" class="input-checkbox checkBoxItem">
                </td>
                <th scope="row"><?php echo $key + 1; ?></th>

                <td><?php echo $pro->title; ?></td>
                <td><?php echo htmlspecialchars($pro->quantity); ?></td>
                <td><?php echo number_format($pro->import_price_one_product, 0, ',', '.'); ?> VNĐ</td>
                <td><?php echo number_format($pro->selling_price, 0, ',', '.'); ?> VNĐ</td>
                <td><?php echo htmlspecialchars($pro->unit); ?></td>

                <td><?php echo date('d-m-Y', strtotime($pro->expiration_date)); ?></td>
                <td>
                    <?php if ($pro->status == 1): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Inactive</span>
                    <?php endif; ?>
                </td>
                <td style="width: auto" class="text-center">
                    <a href="<?php echo base_url('product/edit/' . $pro->product_id); ?>" class="btn btn-success"><i
                            class="fa fa-edit"></i></a>
                    <a href="<?php echo base_url('product/delete/' . $pro->product_id); ?>"
                        onclick="return confirm('Bạn chắc chắn muốn xóa?');" class="btn btn-danger"><i
                            class="fa fa-trash"></i></a>
                    <a href="<?php echo base_url('quantity/update/' . $pro->product_id); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-box"></i> Nhập kho
                    </a>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>

</table>
<div class="text-center"><?php echo $links; ?></div>