<?php

namespace App\Exports;

use App\Models\Group;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GroupsExport implements FromCollection, WithHeadings
{
    public function collection(): Enumerable
    {
        return Group::with('creator')
            ->withCount('members')
            ->orderBy('id')
            ->get()
            ->map(fn (Group $group) => [
                $group->id,
                $group->name,
                $group->creator?->name ?? 'Sin creador',
                ucfirst($group->privacy),
                $group->members_count,
                $group->status === 'active' ? 'Activo' : 'Inactivo',
                $group->created_at?->format('d/m/Y H:i') ?? '',
            ]);
    }

    public function headings(): array
    {
        return ['ID', 'Grupo', 'Creador', 'Privacidad', 'Miembros', 'Estado', 'Creado'];
    }
}
