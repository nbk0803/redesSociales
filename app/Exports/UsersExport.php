<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function __construct(protected ?User $user = null)
    {
    }

    public function collection(): Enumerable
    {
        $query = User::with('memberGroups')->orderBy('id');

        if ($this->user) {
            $query->whereKey($this->user->id);
        }

        return $query->get()->map(fn (User $user) => [
            $user->id,
            $user->name,
            $user->username ?? 'N/A',
            $user->email,
            $user->memberGroups->pluck('name')->implode(', '),
            $user->status === 'active' ? 'Active' : 'Inactive',
            $user->created_at?->format('m/d/Y H:i') ?? '',
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Username', 'Email', 'Groups', 'Status', 'Registered'];
    }
}