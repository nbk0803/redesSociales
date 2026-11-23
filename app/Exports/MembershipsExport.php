<?php

namespace App\Exports;

use App\Models\GroupMember;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MembershipsExport implements FromCollection, WithHeadings
{
    public function __construct(protected ?string $status = null) {}

    public function collection(): Enumerable
    {
        $roles = [
            'owner' => 'Propietario',
            'admin' => 'Administrador',
            'moderator' => 'Moderador',
            'member' => 'Miembro',
        ];

        return GroupMember::with(['user', 'group', 'registeredBy'])
            ->when(in_array($this->status, ['active', 'inactive'], true), function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->get()
            ->map(fn (GroupMember $membership) => [
                $membership->id,
                $membership->user?->name ?? 'Usuario',
                $membership->group?->name ?? 'N/A',
                $roles[$membership->role] ?? $membership->role,
                $membership->status === 'active' ? 'Activa' : 'Inactiva',
                $membership->registeredBy?->name ?? 'N/A',
                $membership->joined_at?->format('d/m/Y H:i') ?? '',
            ]);
    }

    public function headings(): array
    {
        return ['ID', 'Usuario', 'Grupo', 'Rol', 'Estado', 'Registrado por', 'Se unio'];
    }
}
