<?php

namespace App\Http\Controllers;

use App\Models\TiposDocumentos;
use App\Models\User;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:create user', ['only' => ['create', 'store']]);
    $this->middleware('permission:read user', ['only' => ['index', 'show']]);
    $this->middleware('permission:update user', ['only' => ['edit', 'update']]);
    $this->middleware('permission:delete user', ['only' => ['destroy', 'destroyBulk']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Inertia\Response
   */
  public function index(UserIndexRequest $request)
  {
    $users = User::query();

    if ($request->has('search')) {
      $users->where('name', 'LIKE', "%" . $request->search . "%");
      $users->orWhere('email', 'LIKE', "%" . $request->search . "%");
    }
    if ($request->has(['field', 'order'])) {
      $users->orderBy($request->field, $request->order);
    }
    $role = auth()->user()->roles->pluck('name')[0];
    $roles = Role::get();
    if ($role != 'superadmin') {
      $users->whereHas('roles', function ($query) {
        return $query->where('name', '<>', 'superadmin');
      });
      $roles = Role::where('name', '<>', 'superadmin')->get();
    }

    return Inertia::render('User/Index', [
      'tiposdocumentos' => TiposDocumentos::all(),
      'title' => 'Usuario',
      'filters' => $request->all(['search', 'field', 'order']),
      'users' => $users->with('roles')->with('tipodocumento')->paginate(10),
      'roles' => $roles,
    ]);
  }

  public function store(UserStoreRequest $request)
  {
    DB::beginTransaction();
    try {
      $user = User::create([
        'name' => Str::title($request->name),
        'apellido_paterno' => Str::title($request->apellido_paterno),
        'apellido_materno' => Str::title($request->apellido_materno),
        'tipodocumento_id' => $request->tipo_documento,
        'codigo_poligrafista' => $request->codigo_poligrafista,
        'numero_documento' => $request->numero_documento,
        'telefono' => $request->telefono,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);
      $user->assignRole($request->role);
      DB::commit();
      return back()->with('success', $user->name . ' created successfully.');
    } catch (\Throwable $th) {
      DB::rollback();
      return back()->with('error', 'Error creating ' . $request->name . $th->getMessage());
    }
  }

  public function update(UserUpdateRequest $request, $id)
  {
    DB::beginTransaction();
    try {
      $user = User::findOrFail($id);
      $user->update([
        'name' => Str::title($request->name),
        'apellido_paterno' => Str::title($request->apellido_paterno),
        'apellido_materno' => Str::title($request->apellido_materno),
        'tipodocumento_id' => $request->tipo_documento,
        'codigo_poligrafista' => $request->codigo_poligrafista,
        'numero_documento' => $request->numero_documento,
        'telefono' => $request->telefono,
        'email' => $request->email,
        'password' => $request->password ? Hash::make($request->password) : $user->password,
      ]);
      $user->syncRoles($request->role);
      DB::commit();
      return back()->with('success', $user->name . ' updated successfully.');
    } catch (\Throwable $th) {
      DB::rollback();
      return back()->with('error', 'Error updating ' . $user->name . $th->getMessage());
    }
  }

  public function destroy(User $user)
  {
    try {
      $user->delete();
      return back()->with('success', $user->name . ' deleted successfully.');
    } catch (\Throwable $th) {
      return back()->with('error', 'Error deleting ' . $user->name . $th->getMessage());
    }
  }

}
