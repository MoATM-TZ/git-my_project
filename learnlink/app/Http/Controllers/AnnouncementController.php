<?php
namespace App\Http\Controllers;
use App\Jobs\SendAnnouncementNotifications; use App\Models\CourseClass; use Illuminate\Http\RedirectResponse; use Illuminate\Http\Request;
class AnnouncementController extends Controller { public function store(Request $request, CourseClass $courseClass): RedirectResponse { abort_unless($courseClass->lecturer_id===$request->user()->id,403); $data=$request->validate(['title'=>['required','string','max:255'],'body'=>['required','string','max:10000']]); $announcement=$courseClass->announcements()->create([...$data,'lecturer_id'=>$request->user()->id]); SendAnnouncementNotifications::dispatch($announcement)->afterCommit(); return back()->with('status','Announcement posted; student email and SMS notifications have been queued.'); } }
