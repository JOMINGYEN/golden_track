<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin', 'AdminHomeController@Index');
Route::get('/get-email-admin', 'AdminHomeController@GetEmailResetPassword');
Route::post('/verification-email-admin', 'AdminHomeController@VerificationResetPasswordStaff');
Route::get('/reset-password-admin', 'AdminHomeController@ShowResetPasswordStaff');
Route::post('/reset-password-admin-save', 'AdminHomeController@ResetPasswordStaff');


Route::get('/logout', 'AdminHomeController@Logout');
Route::get('/dashboard', 'AdminHomeController@ShowDashboard');
Route::post('/login', 'AdminHomeController@Login');

//Staff
Route::get('/staff', 'AdminHomeController@ShowStaff');
Route::get('/staff-add', 'AdminHomeController@ShowStaffAdd');
Route::post('/staff-add-save', 'AdminHomeController@StaffAddSave');
Route::get('/staff-edit/{staff_id}', 'AdminHomeController@ShowStaffEdit');
Route::post('/staff-edit-save/{staff_id}', 'AdminHomeController@StaffEditSave');

Route::get('/staff-my-account', 'AdminHomeController@ShowStaffMyAccount');
Route::post('/staff-update-my-account/{staff_id}', 'AdminHomeController@StaffUpdateMyAccount');
Route::get('/staff-my-account-change-password', 'AdminHomeController@ShowStaffChangePassword');
Route::post('/staff-my-account-change-password-save', 'AdminHomeController@StaffChangePasswordSave');

Route::get('/staff-my-account-change-email', 'AdminHomeController@ShowStaffChangeEmail');
Route::post('/staff-my-account-change-email-save', 'AdminHomeController@StaffChangeEmailSave');

Route::get('/admin-change-password-staff', 'AdminHomeController@ShowAdminChangePasswordStaff');
Route::post('/admin-change-password-staff-save', 'AdminHomeController@AdminChangePasswordStaffSave');

Route::get('/admin-change-email-staff', 'AdminHomeController@ShowAdminChangeEmailStaff');
Route::post('/admin-change-email-staff-save', 'AdminHomeController@AdminChangeEmailStaffSave');

//product type admin
Route::get('/product-type', 'ProductTypeController@Index');
Route::get('/product-type-add', 'ProductTypeController@ProductTypeAdd');
Route::post('/product-type-save', 'ProductTypeController@ProductTypeSave');
Route::post('/product-type-save-edit/{pro_type_id}', 'ProductTypeController@ProductTypeSaveEdit');
Route::get('/product-type-edit/{pro_type_id}', 'ProductTypeController@ProductTypeEdit');

Route::get('/unactive-product-type/{pro_type_id}', 'ProductTypeController@UnactiveProductType');
Route::get('/active-product-type/{pro_type_id}', 'ProductTypeController@ActiveProductType');
Route::get('/delete-product-type/{pro_type_id}', 'ProductTypeController@DeleteProductType');

//Brand
Route::get('/brand', 'BrandController@Index');
Route::get('/brand-add', 'BrandController@BrandAdd');
Route::post('/brand-save', 'BrandController@BrandSave');
Route::post('/brand-save-edit/{brand_id}', 'BrandController@BrandSaveEdit');
Route::get('/brand-edit/{brand_id}', 'BrandController@BrandEdit');

Route::get('/unactive-brand/{brand_id}', 'BrandController@UnactiveBrand');
Route::get('/active-brand/{brand_id}', 'BrandController@ActiveBrand');
Route::get('/delete-brand/{brand_id}', 'BrandController@DeleteBrand');

//Collection
Route::get('/collection', 'ProductCollectionController@Index');
Route::get('/collection-add', 'ProductCollectionController@CollectionAdd');
Route::post('/collection-save', 'ProductCollectionController@CollectionSave');
Route::post('/collection-save-edit/{collection_id}', 'ProductCollectionController@CollectionSaveEdit');
Route::get('/collection-edit/{collection_id}', 'ProductCollectionController@CollectionEdit');

Route::get('/unactive-collection/{collection_id}', 'ProductCollectionController@UnactiveCollection');
Route::get('/active-collection/{collection_id}', 'ProductCollectionController@ActiveCollection');
Route::get('/delete-collection/{collection_id}', 'ProductCollectionController@DeleteCollection');

//Coupon
Route::get('/coupon-code', 'CouponCodeController@Index');
Route::get('/coupon-code-add', 'CouponCodeController@CouponCodeAdd');
Route::post('/coupon-code-save', 'CouponCodeController@CouponCodeSave');
Route::post('/coupon-code-save-edit/{coupon_code_id}', 'CouponCodeController@CouponCodeSaveEdit');
Route::get('/coupon-code-edit/{coupon_code_id}', 'CouponCodeController@CouponCodeEdit');

Route::get('/unactive-coupon-code/{coupon_code_id}', 'CouponCodeController@UnactiveCouponCode');
Route::get('/active-coupon-code/{coupon_code_id}', 'CouponCodeController@ActiveCouponCode');
Route::get('/delete-coupon-code/{coupon_code_id}', 'CouponCodeController@CouponCodeDelete');



//Supplier
Route::get('/supplier', 'SupplierController@Index');
Route::get('/supplier-add', 'SupplierController@SupplierAdd');
Route::post('/supplier-save', 'SupplierController@SupplierSave');
Route::post('/supplier-save-edit/{supplier_id}', 'SupplierController@SupplierSaveEdit');
Route::get('/supplier-edit/{supplier_id}', 'SupplierController@SupplierEdit');
Route::get('/supplier-delete/{supplier_id}', 'SupplierController@SupplierDelete');

Route::get('/unactive-supplier/{supplier_id}', 'SupplierController@UnactiveSupplier');
Route::get('/active-supplier/{supplier_id}', 'SupplierController@ActiveSupplier');



//Header show
Route::get('/headershow', 'HeaderShowController@Index');
Route::get('/headershow-add', 'HeaderShowController@HeaderShowAdd');
Route::post('/headershow-save', 'HeaderShowController@HeaderShowSave');
Route::post('/headershow-save-edit/{headershow_id}', 'HeaderShowController@HeaderShowSaveEdit');
Route::get('/headershow-edit/{headershow_id}', 'HeaderShowController@HeaderShowEdit');
Route::get('/headershow-delete/{headershow_id}', 'HeaderShowController@HeaderShowDelete');

Route::get('/unactive-headershow/{headershow_id}', 'HeaderShowController@UnactiveHeaderShow');
Route::get('/active-headershow/{headershow_id}', 'HeaderShowController@ActiveHeaderShow');

//Slideshow
Route::get('/slideshow', 'SlideShowController@Index');
Route::get('/slideshow-add', 'SlideShowController@SlideShowAdd');
Route::post('/slideshow-save', 'SlideShowController@SlideShowSave');
Route::post('/slideshow-save-edit/{slideshow_id}', 'SlideShowController@SlideShowSaveEdit');
Route::get('/slideshow-edit/{slideshow_id}', 'SlideShowController@SlideShowEdit');
Route::get('/slideshow-delete/{slideshow_id}', 'SlideShowController@SlideShowDelete');

Route::get('/unactive-slideshow/{slideshow_id}', 'SlideShowController@UnactiveSlideShow');
Route::get('/active-slideshow/{slideshow_id}', 'SlideShowController@ActiveSlideShow');

//AboutStore
Route::get('/about-store', 'AboutStoreController@Index');
Route::get('/about-store-add', 'AboutStoreController@AboutStoreAdd');
Route::post('/about-store-save', 'AboutStoreController@AboutStoreSave');
Route::post('/about-store-save-edit/{about_store_id}', 'AboutStoreController@AboutStoreSaveEdit');
Route::get('/about-store-edit/{about_store_id}', 'AboutStoreController@AboutStoreEdit');
Route::get('/about-store-delete/{about_store_id}', 'AboutStoreController@AboutStoreDelete');

Route::get('/unactive-about-store/{about_store_id}', 'AboutStoreController@UnactiveAboutStore');
Route::get('/active-about-store/{about_store_id}', 'AboutStoreController@ActiveAboutStore');

//Product News
Route::get('/product-news', 'ProductNewsController@Index');
Route::get('/product-news-add', 'ProductNewsController@ProductNewsAdd');
Route::post('/product-news-add-save', 'ProductNewsController@ProductNewsAddSave');
Route::get('/product-news-edit/{product_news_id}', 'ProductNewsController@ProductNewsEdit');
Route::post('/product-news-edit-save/{product_news_id}', 'ProductNewsController@ProductNewsEditSave');

Route::get('/unactive-product-news/{product_news_id}', 'ProductNewsController@UnactiveProductNews');
Route::get('/active-product-news/{product_news_id}', 'ProductNewsController@ActiveProductNews');
Route::get('/product-news-delete/{product_news_id}', 'ProductNewsController@ProductNewsDelete');

//Product
Route::get('/product', 'ProductController@Index');
Route::get('/product-add', 'ProductController@ProductAdd');
Route::post('/product-save', 'ProductController@ProductSave');
Route::post('/product-save-edit/{product_id}', 'ProductController@ProductSaveEdit');
Route::get('/product-edit/{product_id}', 'ProductController@ProductEdit');
Route::get('/unactive-product/{product_id}', 'ProductController@UnactiveProduct');
Route::get('/active-product/{product_id}', 'ProductController@ActiveProduct');
Route::get('/product-images/{product_id}', 'ProductController@ShowProductImages');
Route::post('/product-image-add/{product_id}', 'ProductController@ProductImageAdd');
Route::get('/product-images-delete/{product_image_id}', 'ProductController@ProductImageDelete');
Route::get('/product-delete/{product_id}', 'ProductController@ProductDelete');

//Product Import
Route::get('/product-import', 'ProductImportController@Index');
Route::get('/product-import-add-multiple', 'ProductImportController@ProductImportAddMultiple');
Route::post('/product-import-add-queue', 'ProductImportController@ProductImportAddQueue');
Route::get('/product-import-delete-row-queue', 'ProductImportController@ProductImportDeleteRowQueue');
Route::post('/product-import-add-multiple-save', 'ProductImportController@ProductImportAddMultipleSave');

Route::get('/product-import-add', 'ProductImportController@ProductImportAdd');
Route::post('/product-import-add-save', 'ProductImportController@ProductImportAddSave');
Route::get('/product-import-edit/{product_import_id}', 'ProductImportController@ProductImportEdit');
Route::get('/product-import-show-detail/{product_import_id}', 'ProductImportController@ProductImportShowDetail');
Route::post('/product-import-edit-save/{product_import_id}', 'ProductImportController@ProductImportEditSave');
Route::get('/unactive-product-import/{product_import_id}', 'ProductImportController@UnactiveProductImport');
Route::get('/active-product-import/{product_import_id}', 'ProductImportController@ActiveProductImport');

Route::get('/product-import-add-detail/{product_import_id}', 'ProductImportController@ProductImportAddDetail');
Route::post('/product-import-add-detail-save/{product_import_id}', 'ProductImportController@ProductImportAddDetailSave');
Route::get('/product-import-edit-detail/{product_import_detail_id}', 'ProductImportController@ProductImportEditDetail');
Route::post('/product-import-edit-detail-save/{product_import_detail_id}', 'ProductImportController@ProductImportEditDetailSave');

Route::get('/product-import-delete-detail/{product_import_detail}', 'ProductImportController@ProductImportDeletetDetail');

Route::get('/product-import-delete/{product_import_id}', 'ProductImportController@DeleteProductImport');


Route::get('/product-import-select-image', 'ProductImportController@SelectImageProduct');

//Product Discount
Route::get('/product-discount', 'ProductDiscountController@Index');
Route::get('/product-discount-add', 'ProductDiscountController@ProductDiscountAdd');
Route::post('/product-discount-add-save', 'ProductDiscountController@ProductDiscountAddSave');
Route::get('/product-discount-edit/{product_discount_id}', 'ProductDiscountController@ProductDiscountEdit');
Route::post('/product-discount-edit-save/{product_discount_id}', 'ProductDiscountController@ProductDiscountEditSave');
Route::get('/product-discount-delete/{product_discount_id}', 'ProductDiscountController@DeleteProductDiscount');

Route::get('/product-discount-admin-detail/{product_discount_id}', 'ProductDiscountController@ShowProductDiscountDetail');


// Transport Fee
Route::get('/transport-fee', 'TransportFeeController@TransportFee');
Route::post('/select-transport-fee', 'TransportFeeController@SelectTransportFee');
Route::post('/transport-fee-add', 'TransportFeeController@TransportFeeAdd');
Route::get('/select-fee', 'TransportFeeController@SelectFee');

Route::post('/update-fee', 'TransportFeeController@TransportFeeUpdate');
Route::post('/update-fee-day', 'TransportFeeController@TransportFeeUpdateDay');

//Order Admin
Route::get('/order', 'OrderController@Index');
Route::get('/order-add-show-product', 'OrderController@OrderAddShowProduct');
Route::get('/order-add', 'OrderController@OrderAdd');
Route::post('/order-admin-add-row', 'OrderController@OrderAdminAddRow');
Route::get('/order-admin-delete-row', 'OrderController@OrderAdminDeleteRow');
Route::post('/order-admin-add-save', 'OrderController@OrderAdminAddSave');
Route::post('/order-add-save', 'OrderController@OrderAddSave');
Route::get('/order-show-detail/{order_id}', 'OrderController@OrderShowDetail');
Route::get('/order-print-pdf/{order_id}', 'OrderController@OrderPrintPdf');
Route::get('/order-confirm/{order_id}', 'OrderController@OrderConfirm');
Route::get('/order-confirm-payment/{order_id}', 'OrderController@OrderConfirmPayment');
Route::get('/order-confirm-delivery/{order_id}', 'OrderController@OrderConfirmDelivery');
Route::get('/order-canceled/{order_id}', 'OrderController@OrderCanceled');
Route::get('/order-in-transit/{order_id}', 'OrderController@OrderInTransit');
// Route::post('/check-coupon', 'OrderController@CheckCoupon');
// Route::post('/check-transport-fee', 'OrderController@CheckTransportFee');

//Delivery Order
Route::get('/delivery', 'OrderController@GetDelivery');
Route::get('/delivery-show-detail/{order_id}', 'OrderController@DeliveryShowDetail');
Route::get('/update-order-id-delivery', 'OrderController@UpdateOrderIdDelivery');

//Customer
Route::get('/customer', 'CustomerController@ShowAllCustomer');
Route::get('/show-order-customer/{customer_id}', 'CustomerController@ShowAllOrderCustomer');
Route::get('/show-customer-detail/{customer_id}', 'CustomerController@ShowCustomerDetail');

//Comment
Route::get('/comment', 'CommentController@Index');
Route::post('/approval-comment', 'CommentController@ApprovalComment');
Route::get('/show-comment-detail/{comment_id}', 'CommentController@ShowCommentDetail');
Route::post('/admin-reply-to-comment', 'CommentController@AdminReplyToComment');
Route::get('/delete-comment/{comment_id}', 'CommentController@DeleteComment');
Route::get('/admin-reply-delete-comment/{comment_id}', 'CommentController@DeleteCommentReply');

//Statistical
Route::get('/product-view-statistics', 'StatisticsController@ShowProductViewsStatistics');
Route::get('/search-view-select', 'StatisticsController@SearchViewsSelect');
Route::get('/search-from-to-day-views', 'StatisticsController@SearchFromToDayViews');

Route::get('/product-in-stock-statistics', 'StatisticsController@ShowProductInStockStatistics');
Route::get('/search-product-in-stock-statistics', 'StatisticsController@SearchProductInStockStatistics');

Route::get('/import-statistics', 'StatisticsController@ShowImportStatistics');
Route::get('/search-import-statistics', 'StatisticsController@SearchImportStatistics');
Route::get('/search-select-product-import', 'StatisticsController@SearchSelectImportStatistics');

Route::get('/sales-statistics', 'StatisticsController@ShowSalesStatistics');
Route::get('/search-order-statistics', 'StatisticsController@SearchOrderStatistics');
Route::get('/search-select-order-statistics', 'StatisticsController@SearchSelectOrderStatistics');

Route::post('/export-order-xlsx','StatisticsController@ExportOrderXlsx');
Route::post('/export-import-xlsx','StatisticsController@ExportImportXlsx');
Route::post('/export-views-xlsx','StatisticsController@ExportViewsXlsx');
Route::post('/export-in-stock-xlsx','StatisticsController@ExportInStockXlsx');

//Search
Route::get('/admin-search-staff', 'SearchController@AdminSearchStaff');
Route::get('/admin-search-customer', 'SearchController@AdminSearchCustomer');
Route::get('/admin-search-delivery', 'SearchController@AdminSearchDelivery');
Route::get('/admin-search-order', 'SearchController@AdminSearchOrder');
Route::get('/admin-search-import', 'SearchController@AdminSearchProductImport');
Route::get('/admin-search-comment', 'SearchController@AdminSearchComment');
Route::get('/admin-search-product', 'SearchController@AdminSearchProduct');