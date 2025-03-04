<div class="footer">
    <div class="pull-right">
        10GB of <strong>250GB</strong> Free.
    </div>
    <div>
        <strong>Copyright</strong> Example Company &copy; 2014-2017
    </div>
</div>


</body>


<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function () {
        $('.order_status').change(function () {
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
                    },
                    error: function (xhr, status, error) {
                        alert('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng');
                    }
                });
            }
        });
    });

</script>



<script type="text/javascript">

    function ChangeToSlug() {
        var slug;

        //Lấy text từ thẻ input title 
        slug = document.getElementById("slug").value;
        slug = slug.toLowerCase();
        //Đổi ký tự có dấu thành không dấu
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');
        //Xóa các ký tự đặt biệt
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        //Đổi khoảng trắng thành ký tự gạch ngang
        slug = slug.replace(/ /gi, "-");
        //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
        //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        //Xóa các ký tự gạch ngang ở đầu và cuối
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        //In slug ra textbox có id “slug”
        document.getElementById('convert_slug').value = slug;
    }

</script>

<script>
    document.querySelector('button[type="submit"]').addEventListener('click', function (event) {
        var day = document.querySelector('input[name="quantity_warehouses"]').value;
        var month = document.querySelector('select[name="month"]').value;
        var year = document.querySelector('input[name="import_price_warehouses"]').value;
        var date = year + '-' + month + '-' + day;

        fetch(`<?= base_url('revenueController/index') ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `quantity_warehouses=${day}&month=${month}&import_price_warehouses=${year}`
        }).then(response => response.json())
            .then(data => {
                if (data.custom_revenue && data.custom_revenue.length > 0) {
                    document.getElementById('resultCard').classList.remove('hidden');
                    // Cập nhật dữ liệu hiển thị
                } else {
                    document.getElementById('resultCard').classList.add('hidden');
                }
            });
    });
</script>



</html>