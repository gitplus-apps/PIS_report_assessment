<?php


use App\Http\Controllers\Routecontroller;

use App\Http\Controllers\Staff\StaffRouteController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\StudentHomeworkController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Api\HomeworkApiController;
use App\Http\Controllers\Api\StudentHomeworkApiController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\SemesterController;
use App\Models\Homework;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewAssessmentController;
use App\Http\Controllers\CommentController;
use Dom\Comment;
use Livewire\Livewire;
use App\Http\Controllers\AssessmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|confirmSave
*/

Route::middleware(['auth'])->group(function(){
    Route::get('supplier',[SupplierController::class,'showPage']);
});

Route::get('/', [Routecontroller::class, 'dashboard'])->name('dashboard');

Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
Route::get('/staff/students/{course_id}', [StaffController::class, 'viewStudents'])->name('staff.students');
Route::get('/staff/student/profile/{student_id}', [StaffController::class, 'viewStudentProfile'])->name('staff.student.profile');


Route::get('/student', [Routecontroller::class, 'student'])->name('student');
Route::get("student/transcript/download/{code}",[Routecontroller::class,"dwTranscript"])->where('code', '.*');;
Route::get('req', [RouteController::class,"req"])->name('req');
Route::get('/batch', [Routecontroller::class, 'batch'])->name('batch');
Route::get('/department', [Routecontroller::class, 'department'])->name('department');
Route::get('/staff', [Routecontroller::class, 'staff'])->name('staff');
Route::get('/applications', [Routecontroller::class, 'applications'])->name('applications');
//Route::get('/assignment', [Routecontroller::class, 'assignment'])->name('assignment');
Route::get('/transcript', [Routecontroller::class, 'transcript'])->name('transcript');
Route::get('/library', [Routecontroller::class, 'library'])->name('library');
Route::get('/bill', [Routecontroller::class, 'bill'])->name('bill');
Route::get('/payment', [Routecontroller::class, 'payment'])->name('payment');
Route::get('/courses', [Routecontroller::class, 'admincourse']);
Route::get('expenditure',[Routecontroller::class,'expenditure']);
Route::get('/program', [Routecontroller::class, 'program'])->name('program');
Route::get('/messaging', [Routecontroller::class, 'messaging'])->name('messaging');
Route::get('/settings', [Routecontroller::class, 'manageUser'])->name('manageuser');
Route::get('/library', [Routecontroller::class, 'library'])->name('library');
Route::get("/payment", [Routecontroller::class, 'payment'])->name('payment');
Route::get('/services', [Routecontroller::class, 'services'])->name('services');
Route::get('/inventory', [Routecontroller::class, 'inventory'])->name('inventory');
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');


Route::prefix('students')->group(function () {
    Route::post('/', [StudentsController::class, 'store']);
});


// Route::prefix('lecturer')->group(function () {
//     Route::get('/students', [StaffRouteController::class, 'student'])->name('student');
//     Route::get('/message', [StaffRouteController::class, 'messaging'])->name('message');
//     Route::get('/notice', [StaffRouteController::class, 'notice'])->name('notice');

//     Route::get('/homework', [HomeworkController::class, 'index'])->name('homework.index');
//     Route::post('/homework/upload', [HomeworkController::class, 'uploadHomework'])->name('homework.upload');
//     Route::get('/homework/download/{id}', [HomeworkController::class, 'downloadHomework'])->name('homework.download');
//     Route::post('/homework/submit/{id}', [HomeworkController::class, 'submitHomework'])->name('homework.submit');
//     Route::get('/logout', function () {
//         Auth::logout();
//         return redirect("/");
//     });
// });

Route::prefix('lecturer')->middleware('auth')->group(function () {
    Route::get('/students', [StaffRouteController::class, 'student'])->name('student');
    Route::get('/message', [StaffRouteController::class, 'messaging'])->name('message');
    Route::get('/notice', [StaffRouteController::class, 'notice'])->name('notice');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect("/");
    });
});


Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    
    Route::get('/notices', [NoticeController::class, 'index'])->name('notice.index');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notice.store');
    Route::get('/notices/{id}/edit', [NoticeController::class, 'edit'])->name('notice.edit');
    Route::put('/notices/{id}', [NoticeController::class, 'update'])->name('notice.update');
    Route::delete('/notices/{id}', [NoticeController::class, 'delete'])->name('notice.delete');
    
});



Route::prefix('timetables')->name('timetables.')->group(function () {
    Route::get('/', [TimetableController::class, 'index'])->name('index');
    Route::get('/timetables/create', [TimetableController::class, 'create'])->name('timetables.create');
    Route::post('/', [TimetableController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [TimetableController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TimetableController::class, 'update'])->name('update');
    Route::delete('/{id}', [TimetableController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/studenthomeworks', [StudentHomeworkController::class, 'index'])->name('studenthomeworks.index');
    Route::post('/studenthomeworks/store', [StudentHomeworkController::class, 'store'])->name('studenthomeworks.store');

    Route::get('/homeworks', [HomeworkController::class, 'index'])->name('homework.index');
    Route::post('/homeworks/store', [HomeworkController::class, 'store'])->name('homework.store');
    Route::get('/homeworks/{id}/edit', [HomeworkController::class, 'edit'])->name('homework.edit');
    Route::put('/homeworks/{id}', [HomeworkController::class, 'update'])->name('homework.update');
    Route::delete('/homeworks/{id}', [HomeworkController::class, 'delete'])->name('homework.delete');
});


Route::prefix('branch')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('branch.index');
    Route::post('/store', [BranchController::class, 'store'])->name('branch.store');
    Route::put('/update/{id}', [BranchController::class, 'update'])->name('branch.update');
    Route::delete('/delete/{id}', [BranchController::class, 'delete'])->name('branch.delete');
});

Route::prefix('semester')->group(function () {
    Route::get('/', [SemesterController::class, 'index'])->name('semester.index');
    Route::post('/store', [SemesterController::class, 'store'])->name('semester.store');
    Route::put('/update/{id}', [SemesterController::class, 'update'])->name('semester.update');
    Route::delete('/delete/{id}', [SemesterController::class, 'delete'])->name('semester.delete');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/attendance/get-students', [AttendanceController::class, 'getStudents'])->name('attendance.getStudents');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/staffattendance', [StaffAttendanceController::class, 'index'])->name('staffattendance.index');
    Route::get('/staffattendance/create', [StaffAttendanceController::class, 'create'])->name('staffattendance.create');
    Route::post('/staffattendance', [StaffAttendanceController::class, 'store'])->name('staffattendance.store');
    Route::post('/staffattendance/get-staff', [StaffAttendanceController::class, 'getStaffs'])->name('staffattendance.getStaffs');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/newassessment', [NewAssessmentController::class, 'index'])->name('newassessment.index'); 
//     Route::post('/newassessment/store', [NewAssessmentController::class, 'store'])->name('newassessment.store');
//     Route::get('/newassessment/edit/{transid}', [NewAssessmentController::class, 'edit'])->name('newassessment.edit');
//     Route::post('/newassessment/update/{transid}', [NewAssessmentController::class, 'update'])->name('newassessment.update');
//     Route::get('/newassessment/filter/{id?}', [NewAssessmentController::class, 'filter'])->name('newassessment.filter');
//     Route::get('/get-assessment/{id}', [NewAssessmentController::class, 'getAssessment'])-> name('newassessment.getAssessment');
//     Route::post('/fetch-assessments', [NewAssessmentController::class, 'fetchAssessments'])->name('newassessment.fetchAssessments');
//     Route::post('/newassessment/delete', [NewAssessmentController::class, 'destroy'])->name('newassessment.delete');

// });

Route::middleware(['auth'])->group(function () {
    Route::get('/reportassessment', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/reportassessment/create', [AssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/get-assessment-items', [AssessmentController::class, 'getAssessmentItems'])->name('assessments.getItems');
    Route::post('/reportassessment/store', [AssessmentController::class, 'store'])->name('assessments.store');
    //Route::get('/assessment/view/{student}/{academicYear}/{term}', [AssessmentController::class, 'view'])->name('assessments.view');
    Route::get('/pdf/{student}/{academicYear}/{term}', [AssessmentController::class, 'generatePDF'])->name('assessments.pdf');
    //Route::get('/assessment/edit/{student}/{academicYear}/{term}', [AssessmentController::class, 'edit'])->name('assessments.edit');
    //Route::post('/assessment/update/{student}/{academicYear}/{term}', [AssessmentController::class, 'update'])->name('assessments.update');
    
    Route::get('/reportassessment/view/{student}/{startYear}/{endYear}/{term}', [AssessmentController::class, 'view'])
    ->where(['startYear' => '[0-9]{4}', 'endYear' => '[0-9]{4}', 'term' => '[1-3]'])
    ->name('assessments.view');

    Route::get('/reportassessment/edit/{student}/{startYear}/{endYear}/{term}', [AssessmentController::class, 'edit'])
    ->where(['startYear' => '[0-9]{4}', 'endYear' => '[0-9]{4}', 'term' => '[1-3]'])
    ->name('assessments.edit');

    Route::post('/reportassessment/update/{student}/{startYear}/{endYear}/{term}', [AssessmentController::class, 'update'])
    ->where(['startYear' => '[0-9]{4}', 'endYear' => '[0-9]{4}', 'term' => '[1-3]'])
    ->name('assessments.update');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/reportcomments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/reportcomments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/reportcomments/{transid}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/reportcomments/{transid}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect("/");
});

Route::get('/forgot_password', function () {
    return view("auth.forgot_password");
});
require __DIR__ . '/auth.php';