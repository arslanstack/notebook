<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $search_query = $request->input('search_query');
        if ($request->has('search_query') && !empty($search_query)) {
            $query->where(function ($query) use ($search_query) {
                $query->where('fname', 'like', '%' . $search_query . '%')
                    ->orWhere('lname', 'like', '%' . $search_query . '%')
                    ->orWhere('email', 'like', '%' . $search_query . '%')
                    ->orWhere('phone', 'like', '%' . $search_query . '%')
                    ->orWhereHas('company', function ($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%');
                    });
            });
            
        }
        $data['users'] = $query->orderBy('id', 'DESC')->paginate(50);
        $data['searchParams'] = $request->all();
        return view('admin/users/manage_users', $data);
    }

    public function details($id){
        $data['user'] = User::find($id);
        $data['company'] = $data['user']->company;
        return view('admin/users/users_details', $data);
    }
}