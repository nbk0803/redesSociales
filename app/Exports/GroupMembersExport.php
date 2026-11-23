<?php

namespace App\Exports;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GroupMembersExport implements FromCollection, WithHeadings
{
    public function __construct(protected Group $group) {}

    public function collection(): Enumerable
    {
        $roles = [
            'owner' => 'Propietario',
            'admin' => 'Administrador',
            'moderator' => 'Moderador',
            'member' => 'Miembro',
        ];

        return GroupMember::with(['user', 'registeredBy'])
            ->where('group_id', $this->group->id)
            ->orderBy('id')
            ->get()
            ->map(fn (GroupMember $membership) => [
                $membership->id,
                $membership->user?->name ?? 'Usuario',
                $membership->user?->username ?? 'N/A',
                $membership->user?->email ?? 'N/A',
                $roles[$membership->role] ?? $membership->role,
                $membership->status === 'active' ? 'Activo' : 'Inactivo',
                $membership->registeredBy?->name ?? 'N/A',
                $membership->joined_at?->format('d/m/Y H:i') ?? '',
            ]);
    }

    public function headings(): array
    {
        return ['ID', 'Miembro', 'Usuario', 'Correo', 'Rol', 'Estado', 'Registrado por', 'Se unio'];
    }
}
