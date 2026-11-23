<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $status = $request->query('status');

        $memberships = GroupMember::with(['user', 'group', 'registeredBy'])
            ->when(in_array($status, ['active', 'inactive'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $counts = [
            'all' => GroupMember::count(),
            'active' => GroupMember::where('status', 'active')->count(),
            'inactive' => GroupMember::where('status', 'inactive')->count(),
        ];

        return view('memberships.index', compact('memberships', 'status', 'counts'));
    }
}
