<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Website\SeoSettingController;
use App\Http\Controllers\Api\Website\WebsiteSettingController;
use App\Http\Controllers\Api\Website\CategoryController;
use App\Http\Controllers\Api\Website\SubcategoryController;
use App\Http\Controllers\Api\Website\ProductController;
use App\Http\Controllers\Api\Website\ProductImageController;
use App\Http\Controllers\Api\Website\ProductFeatureController;
use App\Http\Controllers\Api\Website\ProductSpecificationController;
use App\Http\Controllers\Api\Website\ProductReviewController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\Website\BannerController;
use App\Http\Controllers\Api\Website\HowItWorkController;
use App\Http\Controllers\Api\Website\GoalSectionController;
use App\Http\Controllers\Api\Website\GoalController;
use App\Http\Controllers\Api\Website\WhyChoseUsController;
use App\Http\Controllers\Api\Website\PaymentSettingController;
use App\Http\Controllers\Api\Website\CheckoutController;
use App\Http\Controllers\Api\Website\PaymentController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Website\ProductVariantController;
use App\Http\Controllers\Api\Website\BlogController;
use App\Http\Controllers\Api\Website\AboutPageController;
use App\Http\Controllers\Api\Website\CmsPageController;
use App\Http\Controllers\Api\Website\ShortVideoController;


// Public Route Start ======================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::get('/seo/{pageKey}', [SeoSettingController::class, 'show']);

Route::get('faqs', [FaqController::class, 'getFaqs']);
Route::post('contact', [ContactController::class, 'store']);
Route::get('/banners', [BannerController::class, 'userIndex']);
Route::get('/how-it-works', [HowItWorkController::class, 'userIndex']);
Route::get('/goals', [GoalSectionController::class, 'userIndex']);

Route::get('why-choose-us', [WhyChoseUsController::class, 'userIndex']);
Route::get('category', [CategoryController::class, 'userIndex']);
Route::get('category/{slug}', [CategoryController::class, 'showProducts']);
Route::get('subcategory/{category_id}', [SubcategoryController::class, 'userIndex']);
Route::get('subcategory', [SubcategoryController::class, 'index']);
Route::get('subcategory-products/{slug}', [SubcategoryController::class, 'productsBySubcategory']);
Route::get('products', [ProductController::class, 'userIndex']);
Route::get('products/{slug}', [ProductController::class, 'showProductDetails']);
Route::get('feature-products', [ProductController::class, 'getFeaturedProducts']);
Route::get('premium-product', [ProductController::class, 'premiumProduct']);

Route::get('/website-settings', [WebsiteSettingController::class, 'show']);

Route::get('blogs', [BlogController::class, 'index']);
Route::get('blogs/{slug}', [BlogController::class, 'showBySlug']);
Route::get('short-video',[ProductReviewController::class, 'shortsReview']);

Route::get('about', [AboutPageController::class, 'userIndex']);
Route::get('cms-pages', [CmsPageController::class, 'userIndex']);
Route::get('cms-pages/{slug}', [CmsPageController::class, 'cmsDetails']);

Route::get('/short-videos', [ShortVideoController::class, 'userIndex']);



/*
|--------------------------------------------------------------------------
| Protected routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

 
    Route::middleware('role:admin,sales,accounts')->prefix('admin')->group(function () {

        Route::get('/dashboard', function () {
            return ['message' => 'Admin area'];
        });

        // all admin API here

        Route::post('/add-employee', [AuthController::class, 'addEmployee']);
        Route::get('/employee-details', [AuthController::class, 'employeeDetails']);

        Route::get('/seo', [SeoSettingController::class, 'index']);
        Route::post('/seo', [SeoSettingController::class, 'store']);
        Route::get('/seo/{id}', [SeoSettingController::class, 'edit']);
        Route::put('/seo/{id}', [SeoSettingController::class, 'update']);
        Route::delete('/seo/{id}', [SeoSettingController::class, 'destroy']);

        Route::post('/website-settings', [WebsiteSettingController::class, 'store']);
        Route::get('/website-settings', [WebsiteSettingController::class, 'edit']);
        Route::post('/website-settings-update/{id}', [WebsiteSettingController::class, 'update']);

        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('subcategories', SubcategoryController::class);
        
        Route::apiResource('products', ProductController::class);
        Route::get('product-images/product/{product_id}', [ProductImageController::class, 'showByProduct']);
        Route::apiResource('product-images', ProductImageController::class)->only(['index','store','destroy']);
        Route::get('product-features/product/{product_id}', [ProductFeatureController::class, 'index']);
        Route::apiResource('product-features', ProductFeatureController::class)->only(['store','destroy']);
        Route::get('product-specifications/product/{product_id}', [ProductSpecificationController::class, 'index']);
        Route::apiResource('product-specifications', ProductSpecificationController::class)->only(['store','destroy']);
        Route::get('product-reviews/product/{product_id}', [ProductReviewController::class, 'showByProduct']);
        Route::apiResource('product-reviews', ProductReviewController::class);

        Route::get('faqs/all', [FaqController::class, 'index']);
        Route::post('faqs', [FaqController::class, 'store']);
        Route::get('faqs/{id}', [FaqController::class, 'show']);
        Route::put('faqs/{id}', [FaqController::class, 'update']);
        Route::delete('faqs/{id}', [FaqController::class, 'destroy']);

        Route::get('contacts', [ContactController::class, 'index']);
        Route::get('contacts/{id}', [ContactController::class, 'show']);
        Route::patch('contacts/status/{id}', [ContactController::class, 'updateStatus']);
        Route::delete('contacts/{id}', [ContactController::class, 'destroy']);

        Route::post('/banners/update/{id}', [BannerController::class, 'update']);
        Route::apiResource('banners', BannerController::class);

        Route::apiResource('how-it-works', HowItWorkController::class);

        Route::get('goal-sections', [GoalSectionController::class, 'index']);
        Route::post('goal-sections', [GoalSectionController::class, 'store']);
        Route::get('goal-sections/{id}', [GoalSectionController::class, 'show']);
        Route::post('goal-sections/update/{id}', [GoalSectionController::class, 'update']);
        Route::delete('goal-sections/delete/{id}', [GoalSectionController::class, 'destroy']);

        Route::get('goals', [GoalController::class, 'index']);
        Route::post('goals', [GoalController::class, 'store']);
        Route::get('goals/{id}', [GoalController::class, 'show']);
        Route::post('goals/update/{id}', [GoalController::class, 'update']);
        Route::delete('goals/delete/{id}', [GoalController::class, 'destroy']);

        Route::apiResource('why-choose-us', WhyChoseUsController::class);

        Route::get('/payment-settings', [PaymentSettingController::class, 'index']);
        Route::post('/payment-settings/store', [PaymentSettingController::class, 'store']);
        Route::get('/payment-settings/{provider}', [PaymentSettingController::class, 'show']);
        Route::post('/payment-settings/status/{id}', [PaymentSettingController::class, 'updateStatus']);

        Route::get('/orders',[OrderController::class,'index']);
        Route::get('/orders/{id}',[OrderController::class,'show']);
        Route::post('/orders/status/{id}',[OrderController::class,'updateStatus']);
        Route::post('/orders/payment/{id}',[OrderController::class,'updatePayment']);

        Route::get('/dashboard',[DashboardController::class,'dashboardCounts']);

        Route::get('product-variants/{product_id}',[ProductVariantController::class,'index']);
        Route::post('product-variants',[ProductVariantController::class,'store']);
        Route::get('product-verients/{id}',[ProductVariantController::class,'edit']);
        Route::post('product-variants/update/{id}',[ProductVariantController::class,'update']);
        Route::delete('product-variants/{id}',[ProductVariantController::class,'destroy']);

        Route::get('blogs', [BlogController::class, 'adminIndex']);
        Route::post('blogs', [BlogController::class, 'store']);
        Route::get('blogs/{id}', [BlogController::class, 'show']);
        Route::post('blogs/{id}', [BlogController::class, 'update']);
        Route::delete('blogs/{id}', [BlogController::class, 'destroy']);

        // Sales route 
        Route::get('/sales-dashboard',[DashboardController::class,'salesDashboardCounts']);
        Route::get('sales-contacts', [ContactController::class, 'index']);
        Route::get('sales-contacts/{id}', [ContactController::class, 'show']);
        Route::patch('sales-contacts/status/{id}', [ContactController::class, 'updateStatus']);
        
        Route::get('/accounts-dashboard',[DashboardController::class,'accountDashboardCounts']);
        Route::get('/accounts-orders',[OrderController::class,'index']);
        Route::get('/accounts-orders/{id}',[OrderController::class,'show']);
        Route::post('/accounts-orders/status/{id}',[OrderController::class,'updateStatus']);
        Route::post('/accounts-orders/payment/{id}',[OrderController::class,'updatePayment']);

        Route::get('about', [AboutPageController::class, 'index']);
        Route::post('about', [AboutPageController::class, 'store']);
        Route::get('about/{id}', [AboutPageController::class, 'edit']);
        Route::post('about/{id}', [AboutPageController::class, 'update']);
        Route::delete('about/{id}', [AboutPageController::class, 'destroy']);

        Route::get('cms-pages', [CmsPageController::class, 'index']);
        Route::get('cms-pages/{id}', [CmsPageController::class, 'show']);
        Route::post('cms-pages', [CmsPageController::class, 'store']);
        Route::post('cms-pages/{id}', [CmsPageController::class, 'update']);
        Route::delete('cms-pages/{id}', [CmsPageController::class, 'destroy']);

        Route::get('/short-videos', [ShortVideoController::class, 'index']);
        Route::post('/short-videos', [ShortVideoController::class, 'store']);
        Route::get('short-videos/{id}', [ShortVideoController::class, 'show']);
        Route::post('short-videos/{id}', [ShortVideoController::class, 'update']); // using POST for file upload
        Route::delete('short-videos/{id}', [ShortVideoController::class, 'destroy']);

    });

   
    Route::middleware('role:user')->prefix('user')->group(function () {

        Route::get('profile',[AuthController::class,'profile']);
        Route::post('profile/update',[AuthController::class,'updateProfile']);

        Route::post('/checkout',[CheckoutController::class,'checkout']);
        Route::post('/checkout/{id}',[CheckoutController::class,'checkoutCod']);
        Route::get('/payment/razorpay/{order_id}',[PaymentController::class,'createRazorpayOrder']);
        Route::post('/payment/verify',[PaymentController::class,'verifyPayment']);

        Route::get('/my-orders',[CheckoutController::class,'myOrders']);
        Route::get('/order/details/{id}',[CheckoutController::class,'orderDetails']);

        Route::get('/my-compleate-orders',[CheckoutController::class,'myCompleateOrders']);
        Route::get('/my-compleate-orders/details/{id}',[CheckoutController::class,'myCompleateOrdersDetails']);
    
    });




});
