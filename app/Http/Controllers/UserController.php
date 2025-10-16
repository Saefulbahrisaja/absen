<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::select(['id', 'name', 'email'])->with(['kegiatan:id,user_id']);

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sorting (optional)
        if ($request->has('sort') && $request->has('direction')) {
            $query->orderBy($request->sort, $request->direction);
        }
        
        // Pagination
        $users = $query->paginate(3);
        //dd($users->total(), $users->perPage(), $users->currentPage(), $users->lastPage());
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:user,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'min:6'],
        ]);

        $user->update(['password' => bcrypt($request->password)]);
        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function reset(User $user)
    {
        $user->update([
            
            'password' => bcrypt('password123'),
        ]);

        return back()->with('success', 'Akun berhasil direset.');
    }
}

