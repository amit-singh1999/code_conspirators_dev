<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect('/login');
});

  
  Route::get('/view-clear', function() {
    $exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('view:clear');
	return '<h1>View cache cleared</h1>';
});
				

Route::get('subalTesting/{id}', 'App\Http\Controllers\admindashboard\AjaxCaseStudyProposalController@index');

Auth::routes();
Route::get('/magic/{token}', 'App\Http\Controllers\Auth\MagicLoginController@ValidateToken');
Route::get('/sendtoken', 'App\Http\Controllers\Auth\MagicLoginController@sendToken');
Route::get('/quickbook_callback', [App\Http\Controllers\ApiController::class, 'quickbook_callback']);
Route::post('/quickbook_callback', [App\Http\Controllers\ApiController::class, 'quickbook_callback']);
Route::get('/testquickbook', [App\Http\Controllers\ApiController::class, 'testquickbook']);
Route::get('/DueInvoiceHandler', [App\Http\Controllers\ApiController::class, 'DueInvoiceHandler']);

// monitoring task complete in  Invoice management Project 
Route::get('/InvoiceAfterTaskCompletion', [App\Http\Controllers\bitrix\CreateinvoiceController::class, 'GetBitrixTaskDetails']);
Route::post('/InvoiceAfterTaskCompletion', [App\Http\Controllers\bitrix\CreateinvoiceController::class, 'GetBitrixTaskDetails']);
Route::get('/newroute', [App\Http\Controllers\bitrix\CreateinvoiceController::class, 'Callapicontroller']);
//will send email once quote is created
Route::get('/bitrixquote', [App\Http\Controllers\QuoteEmailSenderController::class, 'quotebitrix']);
Route::post('/bitrixquote', [App\Http\Controllers\QuoteEmailSenderController::class, 'quotebitrix']);
Route::get('/dashboard/projectStatus', 'App\Http\Controllers\admindashboard\ProjectStatusController@index');
//Route::get('/emailtasksetup', [App\Http\Controllers\EmailTaskCreatorController::class, 'index']);

//after purposal email sent  to user

Route::get('/personaltemplate', 'App\Http\Controllers\InternalTemplateController@intemplate');
Route::get('/yourtemplate/{id}', 'App\Http\Controllers\ExternalTemplateController@extemplate');
Route::post('/yourtemplate/thankyou', 'App\Http\Controllers\templateinvoiceControllercls@checkboxcreateInvoce')->name('createinvoicefortemplate');
Route::get('/actionplan/{id}', 'App\Http\Controllers\ExternalTemplateController@extemplate');
Route::post('/actionplan/thankyou', 'App\Http\Controllers\templateinvoiceControllercls@checkboxcreateInvoce')->name('createinvoicefortemplate');
Route::post('/sendemailnotifications',[App\Http\Controllers\EmailNotificationController::class, 'sendnotification']);
Route::get('/sendemailnotifications',[App\Http\Controllers\EmailNotificationController::class, 'sendnotification']);
Route::post('/quoteproposallink_updater',[App\Http\Controllers\QuoteLinkUpdateController::class, 'index']);
Route::get('/quoteproposallink_updater',[App\Http\Controllers\QuoteLinkUpdateController::class, 'index']);
//pdf downloading  route 

Route::get('/GenerarePdf/{id}', [App\Http\Controllers\RequestTicket::class, 'generatePDF']);
Route::get('/api', [App\Http\Controllers\ApiController::class, 'api']);
Route::post('/api', [App\Http\Controllers\ApiController::class, 'api']);
Route::post('/api_dev', [App\Http\Controllers\ApiController::class, 'api_dev']);
Route::get('/calendardata', [App\Http\Controllers\calendarfullcontroller::class, 'calendar']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/emailtasksetup', [App\Http\Controllers\EmailTaskCreatorController::class, 'index']);
Route::get('/getsupportcomments', [App\Http\Controllers\EmailTaskCreatorController::class, 'getsupportcomments']);
Route::post('/getsupportcomments', [App\Http\Controllers\EmailTaskCreatorController::class, 'getsupportcomments']);
Route::get('/getcompletedtask', [App\Http\Controllers\EmailTaskCreatorController::class, 'getcompletedtask']);
Route::post('/getcompletedtask', [App\Http\Controllers\EmailTaskCreatorController::class, 'getcompletedtask']);

Route::get('/message', [App\Http\Controllers\HomeController::class, 'message'])->name('message');
Route::get('/project', [App\Http\Controllers\HomeController::class, 'project'])->name('project');
Route::get('/support', [App\Http\Controllers\HomeController::class, 'support'])->name('support');

Auth::routes();
Route::resource('admin', 'App\Http\Controllers\adminController');
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::post('/ticketData', [App\Http\Controllers\RequestTicket::class, 'ticketData'])->name('ticketData');
Route::group(['middleware' => 'auth'], function () {
    
     Route::group(['middleware' => 'Checkadmin'], function () {
        Route::get('/dashboard', 'App\Http\Controllers\admindashboard\admindashboardController@index');
        Route::get('/dashboard/logindetail/{userid}', 'App\Http\Controllers\admindashboard\logindetailcontroller@logighistoryShow');
        Route::get('/dashboard/user/delete/{userid}', 'App\Http\Controllers\admindashboard\admindashboardController@portalUserDelete');
        Route::get('/dashboard/user/edit/{userid}', 'App\Http\Controllers\admindashboard\admindashboardController@portalUserEdit');
        Route::post('/dashboard/user/Update/{userid}', 'App\Http\Controllers\admindashboard\admindashboardController@portalUserEditUpdate')->name('userdetailUpdate');
        
        Route::get('/UptimeRobot','App\Http\Controllers\HomeController@Homelinks');
        Route::get('/dashboard/settings', 'App\Http\Controllers\SettingController@index');
        Route::get('/dashboard/payments', 'App\Http\Controllers\RecurringInvoiceController@index');
        Route::get('/dashboard/payments/addretainer', 'App\Http\Controllers\RecurringInvoiceController@addretaineritem');
        Route::get('/dashboard/payments/edit/{itemid}', 'App\Http\Controllers\RecurringInvoiceController@EditMonthlyItem');
        Route::get('/dashboard/payments/edit/{id}', 'App\Http\Controllers\RecurringInvoiceController@EditMonthlyItem');
        Route::get('/dashboard/payments/sendnow/{id}', 'App\Http\Controllers\RecurringInvoiceController@sendinvoicenow');
        Route::post('/dashboard/payments/saveretainer/', 'App\Http\Controllers\RecurringInvoiceController@SaveLineItem')->name('lineitem.save');
        Route::post('/dashboard/payments/update/{id}', 'App\Http\Controllers\RecurringInvoiceController@UpdateMonthlyItem')->name('payments.update');
       
        Route::post('/dashboard/settings/save', 'App\Http\Controllers\SettingController@save_settings')->name('savesettings');
        Route::get('/dashboard/reporting', 'App\Http\Controllers\admindashboard\TimeTrackingController@reporting');
        
        Route::get('/dashboard/timeTracking', 'App\Http\Controllers\admindashboard\TimeTrackingController@index');
        
        Route::get('/dashboard/timeTrackinggetdata', 'App\Http\Controllers\admindashboard\TimeTrackingController@getTimetrackingAjax');
        
        Route::get('/dashboard/timeTrackinggetdatahere/{singelid}', 'App\Http\Controllers\admindashboard\TimeTrackingController@getTimetrackingSingleData')->name('getSingletime');
        
        Route::post('/dashboard/TimetrackingDownloadAspdf/', 'App\Http\Controllers\admindashboard\TimetrackingresportPdfGeneratecontroller@index')->name('Gerenate-TimereportPDF');
       
        Route::get('/dashboard/timeTrackingsingleppdf/{singelid}', 'App\Http\Controllers\admindashboard\TimeTrackingController@getTimetrackingSinglePDF')->name('getSingletimepdf');
     
        Route::get('/dashboard/salesCommisionReport/ajax', 'App\Http\Controllers\admindashboard\salescommisionController@GetSalesReport');
        
        Route::get('/addUser', 'App\Http\Controllers\admindashboard\UseronboardController@index');
        Route::get('/SearchcontactByEmail', 'App\Http\Controllers\admindashboard\UseronboardController@searchContactbyemail');
        Route::get('/SearchProjectbyprojectName', 'App\Http\Controllers\admindashboard\UseronboardController@Searchprojectbyprojectname');
         Route::get('/SearchcompanyByName', 'App\Http\Controllers\admindashboard\UseronboardController@SearchcompanybyCompanyname');
        Route::get('/SearchProductbyproductName', 'App\Http\Controllers\admindashboard\UseronboardController@Searchproductbyproductname');
        Route::get('/Createauser', 'App\Http\Controllers\admindashboard\UseronboardController@createUser')->name('createuser');     
       
        Route::post('/Createauser', 'App\Http\Controllers\admindashboard\UseronboardController@createUser')->name('createuser');     
             
        Route::get('/dashboard/salesCommisionReport', 'App\Http\Controllers\admindashboard\salescommisionController@index');
        Route::post('/dashboard/salesCommisionReport', 'App\Http\Controllers\admindashboard\salescommisionController@GetSalesReport')->name('salescommision.getreport');
        Route::get('/dashboard/salesCommision/InsertSalesData', 'App\Http\Controllers\admindashboard\salescommisionController@InsertSalesData');
        Route::get('/dashboard/SalesTrackinggetdatahere/{singelid}', 'App\Http\Controllers\admindashboard\salescommisionController@GetSingleSales')->name('getSingleusersales');
        Route::post('/dashboard/SalestrackingDownloadAspdf/', 'App\Http\Controllers\admindashboard\TimetrackingresportPdfGeneratecontroller@index')->name('Gerenate-TimereportPDF');
        Route::get('/dashboard/SalesTrackingsingleppdf/{singelid}', 'App\Http\Controllers\admindashboard\salescommisionController@GetSingleSales')->name('getSingleuserpdf');
       
        Route::get('/dashboard/bonus/InsertBonusData', 'App\Http\Controllers\admindashboard\performanceBonusController@InsertBonusData');
        Route::get('/dashboard/performanceBonusReport', 'App\Http\Controllers\admindashboard\performanceBonusController@index');
        Route::get('/dashboard/performanceBonusReport/ajax', 'App\Http\Controllers\admindashboard\performanceBonusController@GetBonusReport');
        Route::post('/dashboard/performanceBonusReport', 'App\Http\Controllers\admindashboard\performanceBonusControllerr@GetBonusReport')->name('getReport');
        Route::get('/dashboard/BonusTrackinggetdatahere/{singelid}', 'App\Http\Controllers\admindashboard\performanceBonusController@GetSingleBonus')->name('getSingleuserbonus');
        Route::get('/dashboard/BonusTrackingsingleppdf/{singelid}', 'App\Http\Controllers\admindashboard\performanceBonusController@GetSingleBonus')->name('getSingleuserbonuspdf');
      
       
        Route::get('/dashboard/CaseStudy/', 'App\Http\Controllers\admindashboard\Casestudycontroller@show');
        Route::get('/dashboard/CaseStudy/show', 'App\Http\Controllers\admindashboard\Casestudycontroller@show');
        Route::get('/dashboard/CaseStudy/create', 'App\Http\Controllers\admindashboard\Casestudycontroller@index')->name('casestudy.create');
        Route::get('/dashboard/CaseStudy/edit/{id}', 'App\Http\Controllers\admindashboard\Casestudycontroller@EditCasestudyData');
        
        Route::post('/dashboard/CaseStudy/update/{id}', 'App\Http\Controllers\admindashboard\Casestudycontroller@UpdateCasestudyData')->name('casestudy.update');
        
        Route::get('/dashboard/CaseStudy/delete/{id}', 'App\Http\Controllers\admindashboard\Casestudycontroller@DeleteCasestudyData');
     
        Route::Post('/dashboard/CaseStudy', 'App\Http\Controllers\admindashboard\Casestudycontroller@saveCasestudyData')->name('savecasestudy');
    
         
     });
    Route::group(['middleware' => 'CheckOperative'], function () {
      
    });
    Route::group(['middleware' => 'CheckStrategist'], function () {
         
    });
    
    
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
	Route::get('map', function () {return view('pages.maps');})->name('map');
	Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
	Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});
Route::post('store-thingweneed','App\Http\Controllers\ThingsWeNeedController@updatethingsweneed')->name('store.thingsweneed');
?>