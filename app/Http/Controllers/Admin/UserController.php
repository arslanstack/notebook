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
                $query->where('name', 'like', '%' . $search_query . '%')
                    ->orWhere('email', 'like', '%' . $search_query . '%')
                    ->orWhere('phone', 'like', '%' . $search_query . '%');
            });
            
        }
        $data['users'] = $query->orderBy('id', 'DESC')->paginate(50);
        $data['searchParams'] = $request->all();
        return view('admin/users/manage_users', $data);
    }

    public function update_status(Request $request){
        $user = User::where('id', $request->id)->first();

        if(!$user){
            return response()->json(['msg' => 'error', 'response' => 'User not found'], 404);
        }
        if($user->status == 0){
            $user->status = 1;
        }else if($user->status == 1){
            $user->status = 0;
        }

        $query = $user->save();
        if($query){
            return response()->json(['msg' => 'success', 'response' => 'User status updated successfully'], 200);
        }

        return response()->json(['msg' => 'error', 'response' => 'User status not updated. Something went wrong.'], 500);
    }
}