<h2 style="text-align: center; margin-bottom: 30px; color: #FE980F;">Thống kê doanh thu</h2>
<?php if($this->session->flashdata('success')) { ?>
				<div class="alert alert-success"><?php echo $this->session->flashdata('success') ?></div>
			<?php } elseif($this->session->flashdata('error')) { ?>
				<div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
			<?php } ?>
<!-- Doanh thu theo ngày và tháng -->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <!-- Doanh thu theo ngày -->
            <div style="width: 100%; height: 200px" class="card">
                <div class="card-body">
                    <h5>Doanh thu ngày hôm nay</h5>
                    <table class="table">
                        <thead style="border-bottom: 3px solid #FE980F;" class="thead-light">
                            <tr>
                                <th scope="col">Ngày</th>
                                <th scope="col">Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($daily_revenue)) { ?>
                                <?php foreach ($daily_revenue as $revenue) { ?>
                                    <tr style="border-bottom: 2px solid #FE980F;">
                                        <td><?php echo date('d-m-Y', strtotime($revenue->date)); ?></td>
                                        <td><?php echo number_format($revenue->total, 0, ',', '.'); ?> VNĐ</td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2" style="text-align: center;">Hôm nay chưa có đơn hàng nào đã thanh toán</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Doanh thu theo tháng -->
            <div style="width: 100%; height: 200px" class="card">
                <div class="card-body">
                    <h5>Doanh thu tháng này</h5>
                    <table class="table">
                        <thead style="border-bottom: 3px solid #FE980F;" class="thead-light">
                            <tr>
                                <th scope="col">Tháng</th>
                                <th scope="col">Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($monthly_revenue)) { ?>
                                <?php foreach ($monthly_revenue as $revenue) { ?>
                                    <tr style="border-bottom: 2px solid #FE980F;">
                                        <td>Tháng <?php echo $revenue->month; ?> - <?php echo $revenue->year; ?></td>
                                        <td><?php echo number_format($revenue->total, 0, ',', '.'); ?> VNĐ</td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2" style="text-align: center;">Không có dữ liệu</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-sm-8">
            <div style="width: 100%; height: 200px" class="card">
                <div class="card-body">
                <h5>Thống kê bất kỳ (theo ngày cụ thể, theo tháng hoặc năm)</h5>
                    <form id="customRevenueForm" action="<?php echo base_url('revenue-custom'); ?>" method="POST">
                        <table class="table">
                            <thead class="thead-light">
                                <tr style="border-bottom: 3px solid #FE980F;">
                                    <th style="width: 140px;" scope="col">Nhập ngày</th>
                                    <th scope="col">Chọn tháng</th>
                                    <th scope="col">Nhập năm</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <input id="day" name="day" min="1" max="31" type="number"
                                                class="form-control" placeholder="(1-31)">
                                        </div>
                                    </td>
                                    <td>
                                        <div style="width: 120px;" class="form-group">
                                            <select name="month" class="form-control" id="month">
                                                <option selected value="">Chọn tháng</option>
                                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                                    <option value="<?php echo $i; ?>">Tháng <?php echo $i; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input id="year" name="year" min="1" type="number" class="form-control"
                                                placeholder="Năm (VD: 2023)">
                                        </div>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success">Xem</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

            <!-- Kết quả doanh thu tùy chỉnh -->
            <div style="width: 100%; height: 200px" class="card">
                <div class="card-body">
                    <h5>Kết quả tùy chỉnh</h5>

                    <table class="table">
                        <thead style="border-bottom: 3px solid #FE980F;" class="thead-light">
                            <tr>
                                <th scope="col">Ngày/Tháng/Năm</th>
                                <th scope="col">Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($custom_revenue) && !empty($custom_revenue)) { ?>
                                <?php foreach ($custom_revenue as $revenue) { ?>
                                    <tr style="border-bottom: 2px solid #FE980F;">
                                        <td>
                                            <?php
                                            // Kiểm tra nếu có thuộc tính 'date' (ngày đầy đủ), hiển thị ngày
                                            if (isset($revenue->date)) {
                                                echo date("d-m-Y", strtotime($revenue->date)); // Hiển thị ngày theo định dạng dd-mm-yyyy
                                            } elseif (!isset($revenue->month)) {
                                                echo "Năm: " . $revenue->year;
                                            } else {
                                                // Nếu không có thuộc tính 'date', hiển thị tháng và năm
                                                echo "Tháng " . $revenue->month . " - " . $revenue->year;
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo number_format($revenue->total, 0, ',', '.'); ?> VNĐ</td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                        <?php if ($this->session->flashdata('success')) { ?>
                                            <div class="alert alert-success"><?php echo $this->session->flashdata('success') ?>
                                            </div>
                                        <?php } elseif ($this->session->flashdata('error')) { ?>
                                            <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?>
                                            </div>
                                        <?php }else { ?>
                                            <div class="alert alert">Không tìm thấy kết quả</div>
                                        <?php }  
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>






                    </table>
                </div>
            </div>
        </div>
    </div>



</div>
<script>
    // Hàm kiểm tra năm nhuận
    function isLeapYear(year) {
        return (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0));
    }

    // Hàm kiểm tra ngày hợp lệ
    function isValidDate(day, month, year) {
        if (month < 1 || month > 12 || day < 1 || year < 1) {
            return false;
        }

        // Các ngày trong tháng (không tính năm nhuận)
        const daysInMonth = {
            1: 31, 2: isLeapYear(year) ? 29 : 28, 3: 31, 4: 30, 5: 31, 6: 30,
            7: 31, 8: 31, 9: 30, 10: 31, 11: 30, 12: 31
        };

        // Kiểm tra số ngày trong tháng
        return day <= daysInMonth[month];
    }

    // Ràng buộc form khi gửi
    document.getElementById('customRevenueForm').addEventListener('submit', function (event) {
        let day = document.getElementById('day').value;
        let month = document.getElementById('month').value;
        let year = document.getElementById('year').value;

        // Kiểm tra nếu có năm và nếu có ngày/tháng, thực hiện kiểm tra tính hợp lệ
        if (year) {
            if (day && month) {
                if (!isValidDate(day, month, year)) {
                    event.preventDefault(); // Ngừng gửi form
                    alert("Ngày không hợp lệ! Vui lòng kiểm tra lại ngày, tháng và năm.");
                }
            } else if (!day && !month) {
                // Nếu chỉ nhập năm mà không có ngày và tháng, cho phép gửi form
                return true;
            }
        } else {
            alert("Vui lòng nhập đầy đủ thông tin.");
            event.preventDefault(); // Ngừng gửi form
        }
    });
</script>