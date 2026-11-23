<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalGroups = Group::count();
        $totalMemberships = GroupMember::where('status', 'active')->count();

        return view('dashboard', compact('totalUsers', 'totalGroups', 'totalMemberships'));
    }
}
