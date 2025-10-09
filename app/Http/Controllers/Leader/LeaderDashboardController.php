<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GroupRequest;
use App\Models\Group;
use App\Models\GroupEvent;
use App\Models\Polygon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LeaderDashboardController extends Controller
{
    /**
     * Leader home: lists leader's groups, relevant join requests, polygons and events.
     */
    public function index()
    {
        $leader = Auth::user();

        // Leader's groups
        $groups = Group::withCount('members')
            ->with('huntingType')
            ->where('leader_id', $leader->id)
            ->get();

        $groupIds = $groups->pluck('id');
        $typeIds  = $groups->pluck('hunting_type_id')->unique()->values();

        // Pending requests:
        //  A) requests targeting one of the leader's groups
        //  B) OR unassigned requests for hunting types the leader owns
        $requests = GroupRequest::with(['user', 'huntingType', 'group'])
            ->where('status', 'pending')
            ->where(function ($q) use ($groupIds, $typeIds) {
                $q->whereIn('group_id', $groupIds)
                  ->orWhere(function ($qq) use ($typeIds) {
                      $qq->whereNull('group_id')
                         ->whereIn('hunting_type_id', $typeIds);
                  });
            })
            ->latest()
            ->get();

        // Polygons available to the leader (shared or owned by his groups)
        // Adjust this logic to your app's rules (e.g., remove ->orWhereNull if not using shared polygons)
        $polygons = Polygon::query()
            ->whereIn('group_id', $groupIds)
            ->orWhereNull('group_id')
            ->orderBy('name')
            ->get();

        // Events for leader's groups
        $events = GroupEvent::with(['group:id,name,leader_id', 'polygon:id,name,group_id'])
            ->whereIn('group_id', $groupIds)
            ->orderBy('start_at', 'asc')
            ->get();

        return view('leader.dashboard', compact('groups', 'requests', 'polygons', 'events'));
    }

    /**
     * Create a user (legacy). You can keep or remove if you only add via addUserToGroup().
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','string','confirmed','min:8'],
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'email_verified_at' => now(),
            'leader_id'         => Auth::id(),
            'status'            => 'approved',
        ]);

        return redirect()->route('leader.dashboard')->with('success', 'Lietotājs izveidots.');
    }

    /**
     * Create or attach a user directly into a given group.
     * Route: POST /groups/{group}/members
     */
    public function addUserToGroup(Request $request, Group $group)
    {
        abort_unless($group->leader_id === Auth::id(), 403);

        $data = $request->validate([
            'user_id'  => ['nullable', 'exists:users,id'],
            'name'     => ['required_without:user_id', 'string', 'max:255'],
            'email'    => ['required_without:user_id', 'email', 'unique:users,email'],
            'password' => ['required_without:user_id', 'string', 'min:8', 'confirmed'],
            'role'     => ['nullable', 'in:member,co_leader'],
        ]);

        if (!empty($data['user_id'])) {
            $user = User::findOrFail($data['user_id']);
        } else {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'email_verified_at' => now(),
            ]);
        }

        $group->members()->syncWithoutDetaching([
            $user->id => [
                'role'   => $data['role'] ?? 'member',
                'status' => 'active',
            ],
        ]);

        return back()->with('success', 'Lietotājs pievienots grupai.');
    }

    /**
     * Approve a pending request.
     * If unassigned (group_id = null), require target_group_id that the leader owns & matches hunting_type.
     */
    public function acceptRequest(Request $http, GroupRequest $groupRequest)
    {
        $leaderId = Auth::id();

        $group = $groupRequest->group;
        if (!$group) {
            $groupId = $http->input('target_group_id');
            abort_unless($groupId, 422, 'Norādi grupu.');

            $group = Group::where('id', $groupId)
                ->where('leader_id', $leaderId)
                ->where('hunting_type_id', $groupRequest->hunting_type_id)
                ->firstOrFail();
        } else {
            abort_unless($group->leader_id === $leaderId, 403);
        }

        $group->members()->syncWithoutDetaching([
            $groupRequest->user_id => ['role' => 'member', 'status' => 'active'],
        ]);

        $groupRequest->update([
            'status'   => 'approved',
            'group_id' => $group->id,
        ]);

        return back()->with('success', 'Pieprasījums apstiprināts un lietotājs pievienots grupai.');
    }

    /**
     * Reject a pending request (assigned or unassigned).
     */
    public function rejectRequest(GroupRequest $groupRequest)
    {
        $leaderId = Auth::id();

        if ($groupRequest->group_id) {
            abort_unless(optional($groupRequest->group)->leader_id === $leaderId, 403);
        } else {
            $ownsType = Group::where('leader_id', $leaderId)
                ->where('hunting_type_id', $groupRequest->hunting_type_id)
                ->exists();
            abort_unless($ownsType, 403);
        }

        $groupRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Pieprasījums noraidīts.');
    }

    /**
     * Store a new calendar event for a leader's group.
     * Named route used by your blade: leader.events.store
     */
    public function storeEvent(Request $request)
    {
        $data = $request->validate([
            'group_id'     => ['required','integer','exists:groups,id'],
            'polygon_id'   => ['nullable','integer','exists:polygons,id'],
            'start_at'     => ['required','date'],
            'end_at'       => ['nullable','date','after_or_equal:start_at'],
            'title'        => ['required','string','max:255'],
            'meetup_place' => ['nullable','string','max:255'],
            'notes'        => ['nullable','string'],
        ]);

        $group = Group::findOrFail($data['group_id']);
        abort_unless((int)$group->leader_id === (int)Auth::id(), 403);

        if (!empty($data['polygon_id'])) {
            $poly = Polygon::findOrFail($data['polygon_id']);
            // If polygons are scoped to groups, ensure it matches
            if (!is_null($poly->group_id) && (int)$poly->group_id !== (int)$group->id) {
                return back()->withErrors(['polygon_id' => 'Šis poligons nepieder izvēlētajai grupai.'])->withInput();
            }
        }

        GroupEvent::create([
            'group_id'     => $data['group_id'],
            'polygon_id'   => $data['polygon_id'] ?? null,
            'title'        => $data['title'],
            'meetup_place' => $data['meetup_place'] ?? null,
            'notes'        => $data['notes'] ?? null,
            'start_at'     => $data['start_at'],
            'end_at'       => $data['end_at'] ?? null,
            'created_by'   => Auth::id(),
        ]);

        return back()->with('success', 'Notikums izveidots.');
    }

    /**
     * Delete an event (only if the leader owns the event's group).
     * Named route used by your blade: leader.events.destroy
     */
    public function destroyEvent(GroupEvent $event)
    {
        abort_unless(optional($event->group)->leader_id === Auth::id(), 403);

        $event->delete();

        return back()->with('success', 'Notikums dzēsts.');
    }
}
