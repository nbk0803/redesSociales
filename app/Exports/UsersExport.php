<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection(): Enumerable
    {
        return User::with('memberGroups')
            ->orderBy('id')
            ->get()
            ->map(fn (User $user) => [
                $user->id,
                $user->name,
                $user->username ?? 'N/A',
                $user->email,
                $user->memberGroups->pluck('name')->implode(', '),
                $user->status === 'active' ? 'Activo' : 'Inactivo',
                $user->created_at?->format('d/m/Y H:i') ?? '',
            ]);
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Usuario', 'Correo', 'Grupos', 'Estado', 'Registrado'];
    }
}
