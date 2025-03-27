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

/* PAGINATINO */
$route['pagination/index']['GET'] = 'indexController/index/';
$route['pagination/index/(:num)']['GET'] = 'indexController/index/$1';
$route['pagination/danh-muc/(:any)/(:any)']['GET'] = 'indexController/category/$1/$2';
$route['pagination/danh-muc/(:any)/(:any)/(:any)']['GET'] = 'indexController/category/$1/$2/$3';
$route['pagination/thuong-hieu/(:any)/(:any)/(:any)']['GET'] = 'indexController/brand/$1/$2/$3';
$route['pagination/thuong-hieu/(:any)/(:any)']['GET'] = 'indexController/brand/$1/$2';

/* CART */
$route['gio-hang']['GET'] = 'indexController/cart';
$route['add-to-cart']['POST'] = 'indexController/add_to_cart';
$route['update-cart-item']['POST'] = 'indexController/update_cart_item';
$route['delete-all-cart']['GET'] = 'indexController/delete_all_cart';
$route['delete-item/(:any)']['GET'] = 'indexController/delete_item/$1';


/* CKECKOUT */
$route['checkout']['GET'] = 'indexController/checkout';
$route['confirm-checkout-method']['POST'] = 'checkoutController/confirm_checkout_method';

/* LIST ORDER */
$route['order_customer/listOrder']['GET'] = 'indexController/listOrder';
$route['order_customer/update-order-status']['POST'] = 'orderController/update_order_status_COD';
$route['order_customer/viewOrder/(:any)']['GET'] = 'indexController/viewOrder/$1';
$route['order_customer/deleteOrder/(:any)']['GET'] = 'indexController/deleteOrder/$1';

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
$route['customer/list']['GET'] = 'customerController/index';
$route['manage-customer/edit/(:any)']['GET'] = 'customerController/editCustomer/$1';
$route['manage-customer/update/(:any)']['POST'] = 'customerController/updateCustomer/$1';
$route['manage-customer/delete/(:any)']['GET'] = 'customerController/deleteCustomer/$1';

/* REGISTER ADMIN ACCCOUNT */
// $route['login-admin']['POST'] = 'loginController/loginAdmin';
// $route['register-admin']['GET'] = 'loginController/register_admin';
// $route['register-admin-submit']['POST'] = 'loginController/insert_admin';

/* MANAGE BRAND */
$route['brand/list']['GET'] = 'brandController/index';
$route['brand/create']['GET'] = 'brandController/createBrand';
$route['brand/edit/(:any)']['GET'] = 'brandController/editBrand/$1';
$route['brand/store']['POST'] = 'brandController/storeBrand';
$route['brand/update/(:any)']['POST'] = 'brandController/updateBrand/$1';
$route['brand/delete/(:any)']['GET'] = 'brandController/deleteBrand/$1';

/* MANAGE CATEGORY */
$route['category/list']['GET'] = 'categoryController/index';
$route['category/create']['GET'] = 'categoryController/createCategory';
$route['category/edit/(:any)']['GET'] = 'categoryController/editCategory/$1';
$route['category/store']['POST'] = 'categoryController/storeCategory';
$route['category/update/(:any)']['POST'] = 'categoryController/updateCategory/$1';
$route['category/delete/(:any)']['GET'] = 'categoryController/deleteCategory/$1';

/* MANAGE SLIDER */
$route['slider/list']['GET'] = 'sliderController/index';
$route['slider/create']['GET'] = 'sliderController/createSlider';
$route['slider/edit/(:any)']['GET'] = 'sliderController/editSlider/$1';
$route['slider/store']['POST'] = 'sliderController/storeSlider';
$route['slider/update/(:any)']['POST'] = 'sliderController/updateSlider/$1';
$route['slider/delete/(:any)']['GET'] = 'sliderController/deleteSlider/$1';

/* MANAGE PRODUCT */
$route['product/list/(:num)']['GET'] = 'productController/index/$1';
$route['product/list']['GET'] = 'productController/index';
$route['product/create']['GET'] = 'productController/createProduct';
$route['product/store']['POST'] = 'productController/storeProduct';
$route['product/edit/(:any)']['GET'] = 'productController/editProduct/$1';
$route['product/update/(:any)']['POST'] = 'productController/updateProduct/$1';
$route['product/delete/(:any)']['GET'] = 'productController/deleteProduct/$1';

/* MANAGE WAREHOUSE */
$route['warehouse/list']['GET'] = 'warehouseController/index';
$route['warehouse/receive-goods']['GET'] = 'warehouseController/receive_goods';
$route['warehouse/enter-into-warehouse']['POST'] = 'warehouseController/enter_into_warehouse';




$route['quantity/update/(:any)']['GET'] = 'warehouseController/updateQuantityProduct/$1';
$route['product-in-warehouse/delete/(:any)']['GET'] = 'warehouseController/deleteProduct/$1';
$route['warehouse/plusquantity-importpriceinwwarehouses/(:any)']['POST'] = 'warehouseController/plusQuantityWarehouses/$1';



/* MANAGE ORDER */
$route['order_admin/listOrder']['GET'] = 'orderController/index';
$route['order_admin/update-order-status']['POST'] = 'orderController/update_order_status';
$route['order_admin/viewOrder/(:any)']['GET'] = 'orderController/viewOrder/$1';
$route['order_admin/deleteOrder/(:any)']['GET'] = 'orderController/deleteOrder/$1';
$route['order_admin/printOrder/(:any)']['GET'] = 'orderController/printOrder/$1';

/* MANAGE COMMENT */
$route['comment/send']['POST'] = 'dashboardController/comment_send';
$route['comment']['GET'] = 'dashboardController/list_comment';
$route['comment/edit/(:any)']['GET'] = 'dashboardController/editComment/$1';
$route['comment/update/(:any)']['POST'] = 'dashboardController/updateComment/$1';
$route['comment/delete/(:any)']['GET'] = 'dashboardController/deleteComment/$1';

/* REVENUE */
$route['revenue']['GET'] = 'revenueController/index';
$route['revenue-custom']['POST'] = 'revenueController/customRevenue';
$route['revenuee']['GET'] = 'revenueController/revenuee';
$route['revenueee']['POST'] = 'revenueController/revenueee';

$route['revenueReport']['GET'] = 'revenueController/index';
$route['revenueBatches']['GET'] = 'revenueController/batches';


// Phần xử lý AI
// $route['getlayoutAI']['GET'] = 'indexController/AI';
// $route['search-by-disease']['POST'] = 'indexController/AI';
// $route['getlayoutAItest']['GET'] = 'predictionController/index';