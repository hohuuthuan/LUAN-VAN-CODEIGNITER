<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'indexController';

$route['translate_uri_dashes'] = FALSE;


                                            /* USER */
/* USER LOGIN */
$route['dang-nhap']['GET'] = 'indexController/login';
$route['dang-xuat']['GET'] = 'indexController/logout';
$route['login-customer']['POST'] = 'indexController/loginCustomer';

/* REGISTER ACCCOUNT */
$route['dang-ky']['POST'] = 'indexController/dang_ky';
$route['kich-hoat-tai-khoan']['GET'] = 'indexController/kich_hoat_tai_khoan';

/* RECOVER PASSWORD */
$route['forgot-password-layout']['GET'] = 'indexController/forgot_password_layout';
$route['customer/forgot-password']['POST'] = 'indexController/confirm_forgot_password';
$route['lay-lai-mat-khau']['GET'] = 'indexController/lay_lai_mat_khau';
$route['verify-token-forget-password']['POST'] = 'indexController/verify_token_forget_password';
$route['nhap-mat-khau-moi']['GET'] = 'indexController/nhap_mat_khau_moi';
$route['enterNewPassword']['POST'] = 'indexController/enterNewPassword';
$route['verify-token']['POST'] = 'indexController/verify_token';
$route['reset-password']['POST'] = 'indexController/reset_password';

/* CHANGE PASSWORD */
$route['confirmPassword']['GET'] = 'indexController/confirmPassword';
$route['enterPasswordNow']['POST'] = 'indexController/enterPasswordNow';
$route['change-password']['GET'] = 'indexController/change_password';
$route['nhap-ma-xac-thuc']['GET'] = 'indexController/nhap_ma_xac_thuc';
$route['change-password-verify-token']['POST'] = 'indexController/change_password_verify_token';
$route['cap-nhat-mat-khau-moi']['GET'] = 'indexController/cap_nhat_mat_khau_moi';
$route['changePassword']['POST'] = 'indexController/changePassword';

/* PAGE */
$route['404_override'] = 'indexController/page_404';
$route['danh-muc/(:any)/(:any)']['GET'] = 'indexController/category/$1/$2';
$route['thuong-hieu/(:any)/(:any)']['GET'] = 'indexController/brand/$1/$2';
$route['san-pham/(:any)/(:any)']['GET'] = 'indexController/product/$1/$2';

/* PAGINATION HOME */
$route['pagination/index']['GET'] = 'indexController/index';
$route['pagination/index/(:num)']['GET'] = 'indexController/index/$1';
$route['pagination/danh-muc/(:any)/(:any)']['GET'] = 'indexController/category/$1/$2';
$route['pagination/danh-muc/(:any)/(:any)/(:any)']['GET'] = 'indexController/category/$1/$2/$3';
$route['pagination/thuong-hieu/(:any)/(:any)/(:any)']['GET'] = 'indexController/brand/$1/$2/$3';
$route['pagination/thuong-hieu/(:any)/(:any)']['GET'] = 'indexController/brand/$1/$2';


/* PRODUCT ON SALE */
$route['product-on-sale']['GET'] = 'indexController/product_on_sale';


/* CART */
$route['gio-hang']['GET'] = 'indexController/cart';
$route['add-to-cart']['POST'] = 'indexController/add_to_cart';
$route['update-cart-item']['POST'] = 'indexController/update_cart_item';
$route['delete-all-cart']['GET'] = 'indexController/delete_all_cart';
$route['delete-item/(:any)']['GET'] = 'indexController/delete_item/$1';


/* CKECKOUT */
$route['checkout']['GET'] = 'indexController/checkout';
$route['confirm-checkout-method']['POST'] = 'checkoutController/confirm_checkout_method';

/* APPLY COUPON */
$route['apply-coupon']['POST'] = 'indexController/applyCoupon';



/* LIST ORDER */
$route['order_customer/listOrder']['GET'] = 'indexController/listOrder';
$route['order_customer/update-order-status']['POST'] = 'orderController/update_order_status_COD';
$route['order_customer/viewOrder/(:any)']['GET'] = 'indexController/viewOrder/$1';
$route['order_customer/deleteOrder/(:any)']['GET'] = 'indexController/deleteOrder/$1';



/* REVIEW PRODUCT */
$route['review/order/(:any)']['GET'] = 'indexController/reviewProducts/$1';
$route['review/submitReviews']['POST'] = 'indexController/submitReviews';



/* THANK PAGE */
$route['thank-you-for-order']['GET'] = 'checkoutController/thank_you_for_order';
$route['thank-you-for-payment']['GET'] = 'checkoutController/thank_you_for_payment';

/* SEARCH PRODUCT */
$route['search-product']['GET'] = 'indexController/search_product';
$route['search-product/(:any)']['GET'] = 'indexController/search_product/$1';

/* CUSTOMER INFO */
$route['profile-user']['GET'] = 'indexController/profile_user';
$route['customer/edit/(:any)']['GET'] = 'indexController/editCustomer/$1';
$route['customer/update/(:any)']['POST'] = 'indexController/updateCustomer/$1';
$route['customer/update-avatar/(:any)']['POST'] = 'indexController/updateAvatarCustomer/$1';

/* SEND MAIL */
$route['send-mail'] = 'indexController/send_mail';













                                            /* ADMIN */

/* DASHBOARD */
$route['dashboard']['GET'] = 'dashboardController/index';
$route['logout_admin']['GET'] = 'dashboardController/logout';


/* MANAGE CUSTOMER ACCOUNT */
$route['manage-customer/list']['GET'] = 'customerController/index';
$route['manage-customer/list/(:num)']['GET'] = 'customerController/index/$1';
$route['manage-customer/list/edit/(:any)']['GET'] = 'customerController/editCustomer/$1';
$route['manage-customer/update/(:any)']['POST'] = 'customerController/updateCustomer/$1';
$route['manage-customer/bulkUpdate']['POST'] = 'customerController/bulkUpdateCustomer';

$route['manage-customer/delete/(:any)']['GET'] = 'customerController/deleteCustomer/$1';


/* MANAGE ROLE */
$route['manage-role']['GET'] = 'customerController/manageRoleUser';
$route['manage-role/(:num)']['GET'] = 'customerController/manageRoleUser/$1';
$route['manage-role/edit/(:any)']['GET'] = 'customerController/editRole/$1';
$route['manage-role/update/(:any)']['POST'] = 'customerController/updateRole/$1';

/* REGISTER ADMIN ACCCOUNT */
// $route['login-admin']['POST'] = 'loginController/loginAdmin';
// $route['register-admin']['GET'] = 'loginController/register_admin';
// $route['register-admin-submit']['POST'] = 'loginController/insert_admin';

/* MANAGE BRAND */
$route['brand/list']['GET'] = 'brandController/index';
$route['brand/list/(:num)']['GET'] = 'brandController/index/$1';
$route['brand/list/edit/(:any)']['GET'] = 'brandController/editBrand/$1';
$route['brand/create']['GET'] = 'brandController/createBrand';
$route['brand/storage']['POST'] = 'brandController/storageBrand';
$route['brand/update/(:any)']['POST'] = 'brandController/updateBrand/$1';
$route['brand/list/bulkUpdate']['POST'] = 'brandController/bulkUpdateBrand';
// $route['brand/delete/(:any)']['GET'] = 'brandController/deleteBrand/$1';

/* MANAGE CATEGORY */
$route['category/list']['GET'] = 'categoryController/index';
$route['category/list/(:num)']['GET'] = 'categoryController/index/$1';
$route['category/list/edit/(:any)']['GET'] = 'categoryController/editCategory/$1';
$route['category/create']['GET'] = 'categoryController/createCategory';
$route['category/storage']['POST'] = 'categoryController/storageCategory';
$route['category/update/(:any)']['POST'] = 'categoryController/updateCategory/$1';
$route['category/list/bulkUpdate']['POST'] = 'categoryController/bulkUpdateCategory';
// $route['category/delete/(:any)']['GET'] = 'categoryController/deleteCategory/$1';

/* MANAGE SLIDER */
$route['slider/list']['GET'] = 'sliderController/index';
$route['slider/list/edit/(:any)']['GET'] = 'sliderController/editSlider/$1';
$route['slider/create']['GET'] = 'sliderController/createSlider';
$route['slider/store']['POST'] = 'sliderController/storeSlider';
$route['slider/update/(:any)']['POST'] = 'sliderController/updateSlider/$1';
$route['slider/delete/(:any)']['GET'] = 'sliderController/deleteSlider/$1';

/* MANAGE PRODUCT */
$route['product/list']['GET'] = 'productController/index';
$route['product/list/(:num)']['GET'] = 'productController/index/$1';
$route['product/list/edit/(:any)']['GET'] = 'productController/editProduct/$1';
$route['product/create']['GET'] = 'productController/createProduct';
$route['product/store']['POST'] = 'productController/storeProduct';
$route['product/update/(:any)']['POST'] = 'productController/updateProduct/$1';
$route['product/list/bulkUpdate']['POST'] = 'productController/bulkUpdateProduct';

$route['product/delete/(:any)']['GET'] = 'productController/deleteProduct/$1';

/* MANAGE WAREHOUSE */
$route['warehouse/list']['GET'] = 'warehouseController/index';
$route['warehouse/list/(:num)']['GET'] = 'warehouseController/index/$1';
$route['warehouse/receive-goods']['GET'] = 'warehouseController/receive_goods_page';
$route['warehouse/receive-goods/enter-into-warehouse']['POST'] = 'warehouseController/enter_into_warehouse';
$route['warehouse/receive-goods-history']['GET'] = 'warehouseController/receipt_goods_history';
$route['warehouse/receive-goods-history/(:num)']['GET'] = 'warehouseController/receipt_goods_history/$1';
$route['warehouse/receive-goods-history/receipt_detail/(:any)']['GET'] = 'warehouseController/receipt_detail/$1';
$route['receive-goods/bulkPrint']['POST'] = 'warehouseController/bulkPrintReceipts';


/* MANAGE ORDER */
$route['order_admin/listOrder']['GET'] = 'orderController/index';
$route['order_admin/listOrder/(:num)']['GET'] = 'orderController/index/$1';
$route['order_admin/update-order-status']['POST'] = 'orderController/update_order_status';
$route['order_admin/bulkUpdate']['POST'] = 'orderController/bulkUpdate';
$route['order_admin/viewOrder/(:any)']['GET'] = 'orderController/viewOrder/$1';
$route['order_admin/printOrder/(:any)']['GET'] = 'orderController/printOrder/$1';
$route['order_admin/bulkPrint']['POST'] = 'orderController/bulkPrint';
// $route['order_admin/deleteOrder/(:any)']['GET'] = 'orderController/deleteOrder/$1';

/* MANAGE COMMENT */
$route['comment/send']['POST'] = 'dashboardController/comment_send';
$route['comment']['GET'] = 'dashboardController/list_comment';
$route['comment/list/edit/(:any)']['GET'] = 'dashboardController/editComment/$1';
$route['comment/update/(:any)']['POST'] = 'dashboardController/updateComment/$1';
$route['comment/delete/(:any)']['GET'] = 'dashboardController/deleteComment/$1';


/* MANAGE REVIEWS */
$route['review-list']['GET'] = 'reviewController/index';
$route['review-list/(:num)']['GET'] = 'reviewController/index/$1';
$route['review-list/detail/(:num)']['GET'] = 'reviewController/reviewProduct/$1';
$route['review-list/detail/(:num)/(:num)']['GET'] = 'reviewController/reviewProduct/$1/$2';
$route['reply-comment']['POST'] = 'reviewController/updateReview';





/* REVENUE */
$route['revenue']['GET'] = 'revenueController/index';
$route['revenue-custom']['POST'] = 'revenueController/customRevenue';
$route['revenuee']['GET'] = 'revenueController/revenuee';
$route['revenueee']['POST'] = 'revenueController/revenueee';

$route['revenueReport']['GET'] = 'revenueController/revenueReportPage';
$route['revenueBatches']['GET'] = 'revenueController/revenueBatchesPage';





/* AI CHẨN ĐOÁN BỆNH */
$route['predict']['GET'] = 'predictController/yolo_predict_page';
$route['predict']['POST'] = 'predictController/yolo_predict_page';
$route['predict/(:num)']['GET']  = 'predictController/yolo_predict_page/$1';
$route['predict/(:num)']['POST'] = 'predictController/yolo_predict_page/$1';