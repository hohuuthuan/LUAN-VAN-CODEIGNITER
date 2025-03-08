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