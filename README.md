# NIEN_LUAN
Cài đặt dự án, cấu hình database        DONE
Giải thích CSDL                         DONE
Cấu trúc thư mục dự án                  DONE
# Customer
Đăng nhập                               DONE                         
Đăng ký, quên mật khẩu                  DONE
Cấu hình gửi mail                       DONE
Trang chủ, cấu hình phân trang          DONE      
Trang chi tiết sản phẩm, giỏ hàng       DONE                             
Trang đặt hàng                          DONE
Trang danh sách đơn hàng                DONE 
Trang thông tin cá nhân                 DONE
Đổi mật khẩu                            DONE
Tìm kiếm sản phẩm                       DONE
Lọc sản phẩm                            DONE
# Admin
QL Thương hiệu                          DONE
QL Danh mục                             DONE
QL Sản phẩm                             DONE
QL Đơn hàng                             DONE
QL Khách hàng                           DONE
QL SLider                               DONE
QL bình luận                            DONE
Thống kê doanh thu                      DONE
QL kho hàng                             DONE


# Phần AI
python 3.9
CUDA 11.8
ultralytis



# Phân trang
base_url: Đường dẫn cơ bản cho các liên kết phân trang.
total_rows: Tổng số sản phẩm (được lấy từ $total_products).
per_page: Số lượng sản phẩm hiển thị trên mỗi trang.
uri_segment: Vị trí của số trang trong URI (phần thứ 3 trong URI).
use_page_numbers: Sử dụng số trang thay vì offset.
Các thẻ HTML để tạo các liên kết phân trang (first_link, last_link, cur_tag_open, cur_tag_close, ...).




# Thông tin thanh toán VNPAY
Thành công
    Ngân hàng: NCB
    Số thẻ: 9704198526191432198
    Tên chủ thẻ:NGUYEN VAN A
    Ngày phát hành:07/15
    Mật khẩu OTP:123456

Thẻ không đủ số dư
    Ngân hàng: NCB
    Số thẻ: 9704195798459170488
    Tên chủ thẻ:NGUYEN VAN A
    Ngày phát hành:07/15

vnp_Amount=4500000
&vnp_BankCode=NC
&vnp_BankTranNo=VNP1483711
&vnp_CardType=AT
&vnp_OrderInfo=Noi+dung+thanh+toa
&vnp_PayDate=2025030917115
&vnp_ResponseCode=0
&vnp_TmnCode=F72UMWT
&vnp_TransactionNo=1483711
&vnp_TransactionStatus=0
&vnp_TxnRef=616
&vnp_SecureHash=2dd2b18dc16428cf460b751b7debc04000281059852b9127fd6dc30ab560312359225cfa1195e3d8f9bfbc9110913426270df9e44ba4b955a6448bceec6a906a






http://localhost:8000/thank-you-for-order?
vnp_Amount=108000000&
vnp_BankCode=NCB&
vnp_BankTranNo=VNP14842511
&vnp_CardType=ATM
&vnp_OrderInfo=Thanh+toan+don+hang%3A+INA944033
&vnp_PayDate=20250312174721
&vnp_ResponseCode=00
&vnp_TmnCode=F72UMWTL
&vnp_TransactionNo=14842511
&vnp_TransactionStatus=00
&vnp_TxnRef=INA944033&vnp_SecureHash=98b809938be3dee73591d7be10ef48fbe6e424921f1a15508bc0778433525edffdb793ac431e37f6e7640063a6322d77f973781a22e669f1eb42ea6fc1736e96






# Còn lỗi upload hình ảnh của user


chỉnh lại phần dữ liệu default của các bảng

fix date-picker:
   // document.addEventListener("DOMContentLoaded", function() {
    //     let datePicker = document.getElementById("date-picker");
    //     let displayDate = document.getElementById("display-date");

    //     if (datePicker) {
    //         console.log("DatePicker value on load:", datePicker.value);
    //     }

    //     if (datePicker && datePicker.value) {
    //         let parts = datePicker.value.split("-");
    //         if (parts.length === 3) {
    //             let year = parts[0];
    //             let month = parseInt(parts[1], 10);
    //             let day = parseInt(parts[2], 10);

    //             displayDate.innerHTML = `<span class="text-danger">(*)</span> Ngày ${day} Tháng ${month} Năm ${year}`;
    //         }
    //     }
    // });


    // // Cập nhật khi chọn lại ngày
    // document.getElementById("date-picker").addEventListener("change", function() {
    //     let value = this.value;
    //     if (value) {
    //         let parts = value.split("-");
    //         if (parts.length === 3) {
    //             let year = parts[0];
    //             let month = parseInt(parts[1], 10);
    //             let day = parseInt(parts[2], 10);

    //             document.getElementById("display-date").innerHTML = `<span class="text-danger">(*)</span> Ngày ${day} Tháng ${month} Năm ${year}`;
    //         }
    //     }
    // });