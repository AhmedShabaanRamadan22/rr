<?php

// use App\Http\Controllers\Admin\FacilityEmployeeController;


Route::get('notifications-read/{notification_id}', 'NotificationController@readNotificationWithRedirect')->name('readNotificationWithRedirect');
Route::post('notifications-switch-read/{notification_id}', 'NotificationController@switchRead')->name('switchRead');

Route::get('/users/track-location/{user_id}', 'UserController@getUserTrackLocation')->name('users.getUserTrackLocation');
Route::resource('users', UserController::class);
Route::get('dt/users', 'UserController@dataTable')->name('users.datatable');

Route::resource('providers', ProviderController::class);
Route::get('dt/providers', 'ProviderController@dataTable')->name('providers.datatable');

Route::resource('organizations', OrganizationController::class);
Route::get('/organizations/{organization}/get-custom-hostname-status', 'OrganizationController@getCustomHostnameStatus')
    ->name('organizations.getCustomHostnameStatus');
Route::get('dt/organizations', 'OrganizationController@dataTable')->name('organizations.datatable');

Route::resource('facilities', FacilityController::class);
Route::get('dt/facilities', 'FacilityController@dataTable')->name('facilities.datatable');

Route::get('orders-customized', 'OrderController@index_customized')->name('orders-customized.index');
Route::resource('orders', OrderController::class);
Route::get('dt/orders', 'OrderController@dataTable')->name('orders.datatable');

Route::resource('tickets', TicketController::class);
Route::get('dt/tickets', 'TicketController@dataTable')->name('tickets.datatable');

Route::resource('supports', SupportController::class);
Route::get('dt/supports', 'SupportController@dataTable')->name('supports.datatable');

Route::resource('forms', FormController::class);
Route::get('dt/forms', 'FormController@dataTable')->name('forms.datatable');

// Route::resource('questions', OrganizationStageController::class);
Route::get('dt/questions', 'QuestionController@dataTable')->name('questions.datatable');
Route::put('sort/questions', 'QuestionController@sort')->name('questions.sort');

Route::resource('question-banks', QuestionBankController::class);
Route::get('dt/question-banks', 'QuestionBankController@dataTable')->name('question-banks.datatable');

Route::resource('question-types', QuestionTypeController::class);
Route::get('dt/question-types', 'QuestionTypeController@dataTable')->name('question-types.datatable');

Route::resource('regexes', RegexController::class);
Route::get('dt/regexes', 'RegexController@dataTable')->name('regexes.datatable');

Route::resource('sectors', SectorController::class);
Route::get('dt/sectors', 'SectorController@dataTable')->name('sectors.datatable');

Route::resource('classifications', ClassificationController::class);
Route::get('dt/classifications', 'ClassificationController@dataTable')->name('classifications.datatable');

Route::resource('nationalities', NationalityController::class);
Route::get('dt/nationalities', 'NationalityController@dataTable')->name('nationalities.datatable');

Route::resource('monitors', MonitorController::class);
Route::get('dt/monitors', 'MonitorController@dataTable')->name('monitors.datatable');

Route::resource('food', FoodController::class);
Route::get('dt/food', 'FoodController@dataTable')->name('food.datatable');

Route::resource('meals', MealController::class);
Route::get('dt/meals', 'MealController@dataTable')->name('meals.datatable');
Route::post('/meal-status', 'MealController@status')->name('meals.status');
Route::get('/meals-customized', 'MealController@index_customized')->name('meals-customized.index');

Route::resource('food-types', FoodTypeController::class);
Route::get('dt/food-types', 'FoodTypeController@dataTable')->name('food-types.datatable');

Route::resource('periods', PeriodController::class);
Route::get('dt/periods', 'PeriodController@dataTable')->name('periods.datatable');

Route::resource('services', ServiceController::class);
Route::get('dt/services', 'ServiceController@dataTable')->name('services.datatable');

Route::resource('roles', RoleController::class);
Route::get('dt/roles', 'RoleController@dataTable')->name('roles.datatable');

Route::resource('categories', CategoryController::class);
Route::get('dt/categories', 'CategoryController@dataTable')->name('categories.datatable');

Route::resource('facility-employee-positions', FacilityEmployeePositionController::class);
Route::get('dt/facility-employee-positions', 'FacilityEmployeePositionController@dataTable')->name('facility-employee-positions.datatable');

Route::resource('statuses', StatusController::class);
Route::get('dt/statuses', 'StatusController@dataTable')->name('statuses.datatable');

Route::resource('attachment-labels', AttachmentLabelController::class);
Route::get('dt/attachment-labels', 'AttachmentLabelController@dataTable')->name('attachment-labels.datatable');

Route::resource('operation-types', OperationTypeController::class);
Route::get('dt/operation-types', 'OperationTypeController@dataTable')->name('operation-types.datatable');

Route::resource('reasons', ReasonController::class);
Route::get('dt/reasons', 'ReasonController@dataTable')->name('reasons.datatable');

Route::resource('dangers', DangerController::class);
Route::get('dt/dangers', 'DangerController@dataTable')->name('dangers.datatable');

Route::resource('fine-banks', FineBankController::class);
Route::get('dt/fine-banks', 'FineBankController@dataTable')->name('fine-banks.datatable');

Route::resource('bravos', BravoController::class);
Route::get('dt/bravos', 'BravoController@dataTable')->name('bravos.datatable');

Route::resource('senders', SenderController::class);
Route::get('dt/senders', 'SenderController@dataTable')->name('senders.datatable');

Route::resource('messages', MessageController::class);
Route::get('dt/messages', 'MessageController@dataTable')->name('messages.datatable');


Route::resource('stage-banks', StageBankController::class);
Route::get('dt/stage-banks', 'StageBankController@dataTable')->name('stage-banks.datatable');
Route::put('sort/stage-banks', 'StageBankController@sort')->name('stage-banks.sort');

Route::resource('organization-stages', OrganizationStageController::class);
Route::get('dt/organization-stages', 'OrganizationStageController@dataTable')->name('organization-stages.datatable');
Route::put('sort/organization-stages', 'OrganizationStageController@sort')->name('organization-stages.sort');

Route::get('dt/meal-organization-stages', 'MealOrganizationStageController@dataTable')->name('meal-organization-stages.datatable');
Route::get('/meal-organization-stages/{meal_organization_stage_id}', 'MealOrganizationStageController@questions')->name('meal-organization-stages.questions');


Route::resource('submitted-forms', SubmittedFormController::class);
Route::get('dt/submitted-forms', 'SubmittedFormController@dataTable')->name('submitted-forms.datatable');


Route::resource('interview-standards', InterviewStandardController::class);
Route::get('dt/interview-standards', 'InterviewStandardController@dataTable')->name('interview-standards.datatable');


Route::resource('notifications', NotificationController::class);
Route::get('dt/notifications', 'NotificationController@dataTable')->name('notifications.datatable');


Route::resource('mobile-infos', MobileInfoController::class);
Route::get('dt/mobile-infos', 'MobileInfoController@dataTable')->name('mobile-infos.datatable');



Route::resource('facility-evaluations', FacilityEvaluationController::class);
Route::get('dt/facility-evaluations', 'FacilityEvaluationController@dataTable')->name('facility-evaluations.datatable');

// crud_operation_routes
Route::resource('banks', BankController::class);
Route::get('dt/banks', 'BankController@dataTable')->name('banks.datatable');

Route::resource('order-reports', OrderReportController::class);
Route::get('dt/order-reports', 'OrderReportController@dataTable')->name('order-reports.datatable');


// Order Admin
Route::controller(OrderInterviewController::class)->group(function () {
    Route::get("/order-interviews", "index")->name('order-interviews.index');
    Route::post("/order-interviews-status", "update_interview_status")->name('order-interviews.update.status');
    Route::get('/order-interviews/note', 'get_note')->name('order-interviews.note.get');
    Route::post('/order-interviews/note', 'update_note')->name('order-interviews.note.update');
    Route::get('/order-interviews/create/{order_id}', 'create')->name('order-interviews.create');
    Route::post('/order-interviews/store', 'store')->name('order-interviews.store');
    Route::get('/order-interviews/edit/{order_id}', 'edit')->name('order-interviews.edit');
    Route::post('/order-interviews/edit/{order}', 'update_scores')->name('order-interviews.update-scores');
    Route::get('/order-interviews/show/{order_id}', 'show')->name('order-interviews.show');
});
Route::get('dt/interview-orders', 'OrderInterviewController@datatable')->name('interview-orders.datatable');

Route::resource('ibans', IbanController::class);
Route::get('dt/ibans', 'IbanController@dataTable')->name('ibans.datatable');


Route::resource('fines', FineController::class);
Route::get('dt/fines', 'FineController@dataTable')->name('fines.datatable');


Route::resource('departments', DepartmentController::class);
Route::get('dt/departments', 'DepartmentController@dataTable')->name('departments.datatable');

Route::get('candidates-customized', 'CandidateController@index_customized')->name('candidates-customized.index');
Route::resource('candidates', CandidateController::class);
Route::post('dt/candidates', 'CandidateController@dataTable')->name('candidates.datatable');


Route::resource('subjects', SubjectController::class);
Route::get('dt/subjects', 'SubjectController@dataTable')->name('subjects.datatable');


Route::resource('contact-us', ContactUsController::class);
Route::get('dt/contact-us', 'ContactUsController@dataTable')->name('contact-us.datatable');

Route::resource('facility-employees', FacilityEmployeeController::class);
Route::get('dt/facility-employees/{facility_id}', 'FacilityEmployeeController@dataTable')->name('facility_employees.datatable');

Route::get('dt/order-sectors', 'OrderSectorController@dataTable')->name('order-sectors.datatable');
Route::get('dt/forms', 'FormController@dataTable')->name('forms.datatable');

Route::controller(FormAnswerController::class)->group(function () {
    Route::post('form-answers-datatable', 'dataTable')->name('form-answers.datatable');
    Route::get('form-answers/{form_id}', 'index')->name('form-answers.index');
});

Route::controller(FormSectorController::class)->group(function () {
    Route::get('form-sectors/{form}', 'show')->name('form-sectors.show');
    Route::get('dt/form-sectors', 'dataTable')->name('form-sectors.datatable');
});
// crud_operation_routes
