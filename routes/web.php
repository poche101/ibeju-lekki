<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{CheckinController,AdminController,MemberController,PortalController};

// ===== One public URL for everyone =====
Route::get('/',[PortalController::class,'show'])->name('portal.checkin');
Route::post('/checkin',[PortalController::class,'mark'])->name('portal.mark');
Route::get('/register',[PortalController::class,'form'])->name('portal.register');
Route::post('/register',[PortalController::class,'register'])->name('portal.register.store');
Route::get('/admin/login',[PortalController::class,'loginForm'])->name('portal.login');
Route::post('/admin/login',[PortalController::class,'login'])->middleware('throttle:6,1');
Route::get('/admin',[PortalController::class,'adminHome'])->name('portal.admin');

// ===== Per-church pages (admin area is reached after signing in) =====
Route::prefix('{church:slug}')->group(function(){
 Route::get('/',[CheckinController::class,'show'])->name('checkin');
 Route::post('/checkin',[CheckinController::class,'mark'])->name('checkin.mark');
 Route::get('/register',[CheckinController::class,'form'])->name('register');
 Route::post('/register',[CheckinController::class,'register'])->name('register.store');
 Route::get('/admin/login',[AdminController::class,'loginForm'])->name('admin.login');
 Route::post('/admin/login',[AdminController::class,'login'])->middleware('throttle:6,1');
 Route::middleware('church.admin')->prefix('admin')->group(function(){
  Route::get('/',[AdminController::class,'dashboard'])->name('admin');
  Route::get('/attendance',[AdminController::class,'attendance'])->name('admin.attendance');
  Route::get('/export/{type}',[AdminController::class,'export'])->where('type','present|absent')->name('admin.export');
  Route::get('/report',[AdminController::class,'report'])->name('admin.report');
  Route::get('/members',[MemberController::class,'index'])->name('admin.members');
  Route::get('/members/export',[MemberController::class,'export'])->name('admin.members.export');
  Route::get('/members/create',[MemberController::class,'create'])->name('admin.members.create');
  Route::post('/members',[MemberController::class,'store'])->name('admin.members.store');
  Route::get('/members/{id}/edit',[MemberController::class,'edit'])->name('admin.members.edit');
  Route::put('/members/{id}',[MemberController::class,'update'])->name('admin.members.update');
  Route::delete('/members/{id}',[MemberController::class,'destroy'])->name('admin.members.destroy');
  Route::post('/logout',[AdminController::class,'logout'])->name('admin.logout');
 });
});
