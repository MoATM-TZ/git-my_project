<?php
namespace App\Http\Controllers\Admin; use App\Http\Controllers\Controller; use App\Models\CourseClass; use App\Models\Report; use App\Models\Resource; use App\Models\User;
class AdminController extends Controller { public function dashboard(){return view('admin.dashboard',['stats'=>['students'=>User::where('role','student')->count(),'lecturers'=>User::where('role','lecturer')->count(),'classes'=>CourseClass::where('is_archived',false)->count(),'uploads'=>Resource::count(),'downloads'=>Resource::sum('downloads_count'),'reports'=>Report::where('status',Report::STATUS_OPEN)->count()]]);} }
