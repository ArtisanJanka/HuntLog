<?php
// app/Http/Controllers/GroupEventController.php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupEvent;
use App\Models\Polygon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupEventController extends Controller
{
    // Calendar for the current user: shows events from groups they belong to or lead
    public function calendar(Request $request)
    {
        $user = Auth::user();

        $groupIds = Group::query()
            ->where('leader_id', $user->id)
            ->pluck('id')
            ->merge(
                $user->groups()->pluck('groups.id') // from your members pivot
            )
            ->unique()
            ->values();

        $events = GroupEvent::with(['group','polygon'])
            ->whereIn('group_id', $groupIds)
            ->orderBy('starts_at')
            ->get();

        return view('calendar.index', compact('events'));
    }

    // Leaders: form to create event for their group
    public function create(Group $group)
    {
        $this->authorize('create', [GroupEvent::class, $group]);

        // polygons that belong to this group (adjust relation as needed)
        $polygons = Polygon::query()
            ->where('group_id', $group->id)
            ->orderBy('name')
            ->get();

        return view('groups.events.create', compact('group','polygons'));
    }

    public function store(Request $request, Group $group)
    {
        $this->authorize('create', [GroupEvent::class, $group]);

        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'location'    => ['nullable','string','max:255'],
            'polygon_id'  => ['nullable','exists:polygons,id'],
            'starts_at'   => ['required','date'],
            'ends_at'     => ['nullable','date','after_or_equal:starts_at'],
            'all_day'     => ['boolean'],
        ]);

        // ensure polygon (if chosen) belongs to this group
        if (!empty($data['polygon_id'])) {
            abort_unless(
                Polygon::where('id', $data['polygon_id'])->where('group_id', $group->id)->exists(),
                422,
                'Polygon does not belong to this group.'
            );
        }

        $data['group_id']   = $group->id;
        $data['created_by'] = Auth::id();

        GroupEvent::create($data);

        return redirect()->route('groups.show', $group)->with('success', 'Pasākums izveidots.');
    }

    public function edit(GroupEvent $event)
    {
        $this->authorize('update', $event);

        $polygons = Polygon::where('group_id', $event->group_id)->orderBy('name')->get();

        return view('groups.events.edit', compact('event','polygons'));
    }

    public function update(Request $request, GroupEvent $event)
    {
        $this->authorize('update', $event);

        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'location'    => ['nullable','string','max:255'],
            'polygon_id'  => ['nullable','exists:polygons,id'],
            'starts_at'   => ['required','date'],
            'ends_at'     => ['nullable','date','after_or_equal:starts_at'],
            'all_day'     => ['boolean'],
        ]);

        if (!empty($data['polygon_id'])) {
            abort_unless(
                Polygon::where('id', $data['polygon_id'])->where('group_id', $event->group_id)->exists(),
                422,
                'Polygon does not belong to this group.'
            );
        }

        $event->update($data);

        return redirect()->route('groups.show', $event->group_id)->with('success', 'Pasākums atjaunināts.');
    }

    public function destroy(GroupEvent $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return back()->with('success', 'Pasākums dzēsts.');
    }
}
