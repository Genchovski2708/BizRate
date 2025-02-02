<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
            'role' => 'required|in:user,admin',
        ]);

        // Hash the password before storing it
        $validatedData['password'] = bcrypt($validatedData['password']);  // bcrypt encryption



        // Store the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],  // already hashed
            'role' => $validatedData['role']
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }



    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Validation
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:5',
            'role' => 'required|in:user,admin',
        ]);

        // Update user details
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];

        // Handle password change
        if ($request->filled('password')) {
            // Only hash and update if password is provided
            $user->password = bcrypt($validatedData['password']);  // or Hash::make($validatedData['password']);
        }

        // Save changes
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }


    public function destroy(User $user)
    {

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
