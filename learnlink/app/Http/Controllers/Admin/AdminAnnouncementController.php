<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformAnnouncement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminAnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = PlatformAnnouncement::with('author:id,name')->latest()->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('announcements', 'public');
        }

        $request->user()->platformAnnouncements()->create($data);

        return back()->with('status', 'Announcement published.');
    }

    public function edit(PlatformAnnouncement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, PlatformAnnouncement $announcement): RedirectResponse
    {
        $data = $this->validatedData($request);
        if ($request->hasFile('banner_image')) {
            $newImage = $request->file('banner_image')->store('announcements', 'public');
            if ($announcement->banner_image) {
                Storage::disk('public')->delete($announcement->banner_image);
            }
            $data['banner_image'] = $newImage;
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('status', 'Announcement updated.');
    }

    public function destroy(PlatformAnnouncement $announcement): RedirectResponse
    {
        if ($announcement->banner_image) {
            Storage::disk('public')->delete($announcement->banner_image);
        }
        $announcement->delete();

        return back()->with('status', 'Announcement deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'body' => ['required', 'string', 'min:3', 'max:10000'],
            'banner_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'display_type' => ['required', 'in:card,hero_banner,alert_bar'],
            'action_label' => ['nullable', 'string', 'max:80', 'required_with:action_url'],
            'action_url' => ['nullable', 'url:http,https', 'max:2048', 'required_with:action_label'],
        ]);
    }
}
