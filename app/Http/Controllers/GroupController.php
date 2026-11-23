<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $groups = Group::with('creator')->withCount('members')->latest()->paginate(10);

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(GroupRequest $request)
    {
        $validated = $request->validated();

        $group = Group::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'privacy' => $validated['privacy'],
            'creator_id' => Auth::id(),
            'registered_by' => Auth::id(),
            'status' => 'active',
        ]);

        $group->members()->attach(Auth::id(), [
            'role' => 'owner',
            'status' => 'active',
            'registered_by' => Auth::id(),
            'joined_at' => now(),
        ]);

        return redirect()->route('groups.show', $group)->with('success', 'Grupo creado correctamente.');
    }

    public function show(Group $group)
    {
        $userId = Auth::id();

        $isMember = $group->members()
            ->whereKey($userId)
            ->wherePivot('status', 'active')
            ->exists();

        if (in_array($group->privacy, ['private', 'secret'], true) && ! $isMember) {
            return redirect()->route('groups.index')
                ->with('error', 'No tienes acceso a este grupo.');
        }

        $group->load([
            'creator',
            'members' => fn ($query) => $query
                ->orderBy('group_members.role')
                ->orderBy('users.name'),
        ]);

        $isOwner = $group->creator_id === $userId;

        $availableUsers = $isOwner
            ? User::whereNotIn('id', $group->members->pluck('id'))
                ->orderBy('name')
                ->get()
            : collect();

        return view('groups.show', compact('group', 'isMember', 'isOwner', 'availableUsers'));
    }

    public function edit(Group $group)
    {
        if ($group->creator_id !== Auth::id()) {
            return redirect()->route('groups.index')
                ->with('error', 'Only the group creator can edit this group.');
        }

        return view('groups.edit', compact('group'));
    }

    public function update(GroupRequest $request, Group $group)
    {
        if ($group->creator_id !== Auth::id()) {
            return redirect()->route('groups.index')
                ->with('error', 'Only the group creator can edit this group.');
        }

        $group->update($request->validated());

        return redirect()->route('groups.index')->with('success', 'The group was updated successfully.');
    }

    public function destroy(Group $group)
    {
        if ($group->creator_id !== Auth::id()) {
            return redirect()->route('groups.show', $group)
                ->with('error', 'Solo el creador puede eliminar este grupo.');
        }

        try {
            $group->memberships()->delete();
            $group->delete();

            return redirect()->route('groups.index')->with('success', 'Grupo eliminado correctamente.');
        } catch (QueryException $e) {
            Log::error('Error al eliminar el grupo: '.$e->getMessage());

            return redirect()->route('groups.index')->with('error', 'El grupo que desea eliminar tiene información relacionada. Comuníquese con el Administrador.');
        } catch (Throwable $e) {
            Log::error('Error inesperado al eliminar el grupo: '.$e->getMessage());

            return redirect()->route('groups.index')->with('error', 'Ocurrió un error inesperado al eliminar el grupo. Comuníquese con el Administrador.');
        }
    }

    public function join(Group $group)
    {
        $user = Auth::user();

        $membership = $group->members()->whereKey($user->id)->first();

        if ($membership) {
            if ($membership->pivot->status === 'active') {
                return back()->with('info', 'Ya eres miembro de este grupo.');
            }

            $group->members()->updateExistingPivot($user->id, [
                'status' => 'active',
                'registered_by' => $user->id,
                'joined_at' => now(),
            ]);

            return back()->with('success', 'Te has unido nuevamente al grupo.');
        }

        if ($group->privacy === 'secret') {
            return back()->with('error', 'Los grupos secretos solo se pueden unir por invitación.');
        }

        $group->members()->attach($user->id, [
            'role' => 'member',
            'status' => 'active',
            'registered_by' => $user->id,
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Te has unido al grupo.');
    }

    public function leave(Group $group)
    {
        $user = Auth::user();

        if ($group->creator_id === $user->id) {
            return back()->with('error', 'Como propietario no puedes abandonar tu grupo.');
        }

        $group->members()->detach($user->id);

        return back()->with('success', 'Has salido del grupo.');
    }

    public function addMember(Request $request, Group $group)
    {
        if (! $this->isOwner($group)) {
            return back()->with('error', 'Solo el creador puede agregar miembros.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'role' => ['required', Rule::in(['admin', 'moderator', 'member'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($group->members()->whereKey($validated['user_id'])->exists()) {
            return back()->with('error', 'Ese usuario ya pertenece al grupo.');
        }

        $group->members()->attach($validated['user_id'], [
            'role' => $validated['role'],
            'status' => $validated['status'],
            'registered_by' => Auth::id(),
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Miembro agregado correctamente.');
    }

    public function updateMember(Request $request, Group $group, User $user)
    {
        if (! $this->isOwner($group)) {
            return back()->with('error', 'Solo el creador puede actualizar miembros.');
        }

        if (! $group->members()->whereKey($user->id)->exists()) {
            return back()->with('error', 'El usuario no pertenece al grupo.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['owner', 'admin', 'moderator', 'member'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($user->id === $group->creator_id) {
            $validated['role'] = 'owner';
            $validated['status'] = 'active';
        }

        $group->members()->updateExistingPivot($user->id, [
            'role' => $validated['role'],
            'status' => $validated['status'],
            'registered_by' => Auth::id(),
        ]);

        return back()->with('success', 'Miembro actualizado correctamente.');
    }

    public function removeMember(Group $group, User $user)
    {
        if (! $this->isOwner($group)) {
            return back()->with('error', 'Solo el creador puede quitar miembros.');
        }

        if ($user->id === $group->creator_id) {
            return back()->with('error', 'No puedes quitar al creador del grupo.');
        }

        $group->members()->detach($user->id);

        return back()->with('success', 'Miembro eliminado correctamente.');
    }

    public function changeStatus(Request $request)
    {
        $group = Group::find($request->id);
        $group->status = $request->status;
        $group->save();

        return response()->json(['success' => true]);
    }

    private function isOwner(Group $group): bool
    {
        return $group->creator_id === Auth::id();
    }
}
