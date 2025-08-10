<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\User;
use App\Models\Booking;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::with('user')->get();
        // Get all users with role 'worker' and 'user'
        $users = User::where('role', 'worker')->orWhere('role', 'user')->get();
        return view('dashboards.workers.index', compact('workers', 'users'));
    }

    public function create()
    {
        $users = User::where('role', 'worker')->get();
        return view('workers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3',
            'phone' => 'required|string|max:15',
            'role' => 'required|in:worker',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
        ]);
        Worker::create([
            'user_id' => $user->id,
            'availability_status' => 'available',
        ]);

        return redirect()->route('dashboard.workers.index')->with('success', 'Pekerja berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'availability_status' => 'required|in:available,unavailable',
        ]);

        $worker = Worker::findOrFail($id);
        $worker->update(['availability_status' => $request->availability_status]);

        return back()->with('success', 'Status pekerja diupdate.');
    }

    public function destroy($id)
    {
        $worker = Worker::findOrFail($id);
        $worker->delete();

        return redirect()->route('dashboard.workers.index')->with('success', 'Pekerja berhasil dihapus.');
    }

    public function show($id)
    {
        $worker = Worker::with('user')->findOrFail($id);

        return view('dashboard.workers.show', compact('worker'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'phone' => 'string|max:15',
        ]);
        $worker = Worker::with('user')->findOrFail($id);
        $worker->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        $worker->update([
            'availability_status' => $request->availability_status,
        ]);

        return redirect()->route('dashboard.workers.index')->with('success', 'Pekerja berhasil diupdate.');
    }

    public function changeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:worker',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['role' => $request->role]);

        Worker::create([
            'user_id' => $user->id,
            'availability_status' => 'available',
        ]);

        return back()->with('success', 'Role pekerja diupdate.');
    }

}
