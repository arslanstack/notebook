<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaConsumptionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaConsumptionCategory::query();
        $search_query = $request->input('search_query');
        if ($request->has('search_query') && !empty($search_query)) {
            $query->where(function ($query) use ($search_query) {
                $query->where('title', 'like', '%' . $search_query . '%');
            });
        }
        $data['media_categories'] = $query->orderBy('id', 'DESC')->paginate(50);
        $data['searchParams'] = $request->all();
        return view('admin/media_categories/manage_media_categories', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            $missing_fields = [];
            foreach ($validator->errors()->messages() as $key => $value) {
                $missing_fields[] = $key;
            }
            return response()->json(['msg' => 'error', 'response' => 'The following fields are required: ' . implode(', ', $missing_fields)]);
        }

        $media_category = new MediaConsumptionCategory();
        $media_category->title = $request->title;
        $media_category->slug = slugify($request->title);
        $media_category->save();

        if ($media_category->id > 0) {
            return response()->json(['msg' => 'success', 'response' => 'Media consumption category added successfully.']);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
        }
    }

    public function show(Request $request)
    {
        $media_category = MediaConsumptionCategory::where('id', $request->id)->first();
        if (!empty($media_category)) {
            $htmlresult = view('admin/media_categories/media_categories_ajax', compact('media_category'))->render();
            return response()->json(['msg' => 'success', 'response' => $htmlresult]);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Media category not found.']);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            $missing_fields = [];
            foreach ($validator->errors()->messages() as $key => $value) {
                $missing_fields[] = $key;
            }
            return response()->json(['msg' => 'error', 'response' => 'The following fields are required: ' . implode(', ', $missing_fields)]);
        }
        
        $media_category = MediaConsumptionCategory::where('id', $request->id)->first();
        
        if (!empty($media_category)) {
            $media_category->title = $request->title;
            if($request->title != $media_category->title){
                $media_category->slug = slugify($request->title);
            }
            $media_category->save();
            return response()->json(['msg' => 'success', 'response' => 'Media Category updated successfully.']);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Media Category not found.']);
        }
    }
    public function delete(Request $request)
    {
        $media_category = MediaConsumptionCategory::find($request->id);
        if (!empty($media_category)) { 
            if($media_category->mediaConsumptions->count() > 0){
                return response()->json(['msg' => 'error', 'response' => 'Could not delete. This media category has media consumption records.']);
            }
            $media_category->delete();
            return response()->json(['msg' => 'success', 'response' => 'Media Category deleted successfully.']);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Media Category not found.']);
        }
    }
}
