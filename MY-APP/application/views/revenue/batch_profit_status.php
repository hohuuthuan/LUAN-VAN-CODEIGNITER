<table>
    <thead>
        <tr>
            <!-- <th>Batch ID</th>
            <th>Product ID</th>
            <th>Import Date</th>
            <th>Import Price</th> -->
            <th>Total Quantity</th>
            <th>Total Cost</th>
            <th>Total Revenue</th>
            <th>Remaining Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($batches as $batch): ?>
            <tr>
                <!-- <td><?= $batch->Batch_ID ?></td>
                <td><?= $batch->ProductID ?></td>
                <td><?= $batch->Import_date ?></td> -->
                <!-- <td><?= number_format($batch->Import_price) ?> VND</td> -->
                <td><?= $batch->Total_Quantity ?></td>
                <td><?= number_format($batch->Total_Cost) ?> VND</td>
                <td><?= number_format($batch->Total_Revenue) ?> VND</td>
                <td><?= number_format($batch->Remaining_Amount) ?> VND</td>
                <td>
                    <?= $batch->Remaining_Amount > 0 ? 'Not Yet Profitable' : 'Profitable' ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



<table class="table table-striped table-bordered mt-3">
    <thead class="table-light">
        <tr>
            <th>
                <h4>STT</h4>
            </th>
            <th class="width200">
                <h4>Tên nhãn hiệu, qui cách, phẩm chất hàng hoá, vật tư, dụng cụ</h4>
            </th>
            <th class="width120">
                <h4>Mã sản phẩm</h4>
            </th>
            <th class="width120">
                <h4>Đơn vị tính</h4>
            </th>
            <th>
                <h4>Giá nhập/đơn vị sản phẩm</h4>
            </th>
            <th class="width130">
                <h4>Hạn sử dụng</h4>
            </th>
            <th>
                <h4>Số lượng theo chứng từ</h4>
            </th>
            <th>
                <h4>Số lượng thực nhập</h4>
            </th>
            <th>
                <h4>Ghi chú</h4>
            </th>
            <th>
                <h4>Thêm/Xóa</h4>
            </th>
        </tr>
    </thead>
    <tbody id="table-body">
        <tr class="table-product-receive">
            <td></td>
        </tr>
        <tr class="table-product-receive">
            <td>1</td>

        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" class="text-right"><strong>Tổng cộng:</strong></td>
            <td colspan="2">
                <input type="text" id="totalAmount" class="form-control" readonly>
                <input type="hidden" name="sub_total" id="totalAmountHidden">
            </td>
        </tr>
    </tfoot>
</table>