<?php

use App\Http\Controllers\EvaluadosController;
use App\Models\User;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AccesoFormatosController;
use App\Http\Controllers\FormatosController;

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
  return redirect('login');
});

Route::get('/dashboard', function () {
  return Inertia::render('Dashboard', [
    'users' => (int)User::count(),
    'roles' => (int)Role::count(),
    'permissions' => (int)Permission::count(),
  ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
  //Perfiles
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  //Usuarios
  Route::resource('/user', UserController::class)->except('create', 'show', 'edit');
  Route::post('/user/destroy-bulk', [UserController::class, 'destroyBulk'])->name('user.destroy-bulk');
  //Formatos
  Route::get('/formatos', [AccesoFormatosController::class, 'allowAccessToFormat'])->name('formatos.acceso');
  Route::post('/formatos', [AccesoFormatosController::class, 'createAllowAccessToFormat'])->name('formatos.crear.acceso');
  Route::get('/formatos/{id}/accesos', [AccesoFormatosController::class, 'restrictAccessToFormat'])->name('formato.restringir.acceso');
  Route::put('/formatos/actualizar', [AccesoFormatosController::class, 'update'])->name('formatos.actualizar.acceso');
  Route::delete('/formato/{id}/delete', [AccesoFormatosController::class, 'deletedEvaluatedFormat'])->name('formato.evaluado.destroy');
  //Roles
  Route::resource('/role', RoleController::class)->except('create', 'show', 'edit');
  //Permisos
  Route::resource('/permission', PermissionController::class)->except('create', 'show', 'edit');
  //Evaluados
  Route::get('/evaluados',[EvaluadosController::class,'index'])->name('evaluados.index');
  Route::get('/evaluados/{personaid}/parentescos',[EvaluadosController::class,'getDetailsPerson'])->name('evaluados.detalle');
});

/*Validacion de acceso a formatos, solo personas autorizadas*/
Route::get('/formato', [AccesoFormatosController::class, 'index'])->name('formato.index');
Route::post('/formato', [FormatosController::class, 'index'])->name('formato.validar.acceso');
Route::post('/formato/guardar', [FormatosController::class, 'store'])->name('formato.store');
Route::post('/formato/dos', [FormatosController::class, 'formatoDos'])->name('formato.dos');
Route::post('/formato/dos/guardar', [FormatosController::class, 'formatoDosStore'])->name('formato.dos.store');

require __DIR__ . '/auth.php';
