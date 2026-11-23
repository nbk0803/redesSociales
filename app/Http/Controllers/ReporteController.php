<?php

namespace App\Http\Controllers;

use App\Exports\GroupMembersExport;
use App\Exports\GroupsExport;
use App\Exports\MembershipsExport;
use App\Exports\UsersExport;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function gruposPdf()
    {
        $grupos = Group::with('creator')
            ->withCount('members')
            ->orderBy('id')
            ->get();

        $data = [
            'titulo' => 'Reporte General de Grupos',
            'fecha' => now()->format('d/m/Y H:i'),
            'grupos' => $grupos,
        ];

        $pdf = Pdf::loadView('reportes.grupos', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('reporte-grupos.pdf');
    }

    public function grupoPdf(Group $group)
    {
        $group->load([
            'creator',
            'registeredBy',
            'members' => fn ($query) => $query
                ->orderBy('group_members.role')
                ->orderBy('users.name'),
        ]);

        $data = [
            'grupo' => $group,
            'fecha' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('reportes.grupo', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('grupo-'.$group->id.'.pdf');
    }

    public function usuariosPdf()
    {
        $usuarios = User::with('memberGroups')->orderBy('id')->get();

        $data = [
            'titulo' => 'General Users Report',
            'fecha' => now()->format('m/d/Y H:i'),
            'usuarios' => $usuarios,
        ];

        $pdf = Pdf::loadView('reportes.usuarios', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('users-report.pdf');
    }

    public function membresiasPdf(Request $request)
    {
        $status = $request->query('status');

        $membresias = GroupMember::with(['user', 'group', 'registeredBy'])
            ->when(in_array($status, ['active', 'inactive'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();

        $data = [
            'titulo' => 'Reporte General de Membresias',
            'fecha' => now()->format('d/m/Y H:i'),
            'status' => $status,
            'membresias' => $membresias,
        ];

        $pdf = Pdf::loadView('reportes.membresias', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('reporte-membresias.pdf');
    }

    public function gruposExcel()
    {
        return Excel::download(new GroupsExport, 'grupos-general.xlsx');
    }

    public function grupoMiembrosExcel(Group $group)
    {
        return Excel::download(new GroupMembersExport($group), 'grupo-'.$group->id.'-miembros.xlsx');
    }

    public function usuariosExcel()
    {
        return Excel::download(new UsersExport, 'users-general.xlsx');
    }

    public function usuarioPdf(User $user)
    {
        $user->load('memberGroups');

        $data = [
            'user' => $user,
            'fecha' => now()->format('m/d/Y H:i'),
        ];

        $pdf = Pdf::loadView('reportes.usuario', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('user-'.$user->id.'.pdf');
    }

    public function usuarioExcel(User $user)
    {
        return Excel::download(new \App\Exports\UsersExport($user), 'user-'.$user->id.'.xlsx');
    }

    public function membresiasExcel(Request $request)
    {
        $status = $request->query('status');

        return Excel::download(new MembershipsExport($status), 'membresias-general.xlsx');
    }
}
