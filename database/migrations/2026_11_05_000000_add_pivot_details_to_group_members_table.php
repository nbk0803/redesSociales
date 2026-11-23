<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            if (! Schema::hasColumn('group_members', 'status')) {
                $table->string('status')->default('active')->after('role');
            }

            if (! Schema::hasColumn('group_members', 'joined_at')) {
                $table->timestamp('joined_at')->nullable()->after('status');
            }
        });

        DB::table('group_members')
            ->whereNull('joined_at')
            ->update(['joined_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)')]);

        $duplicates = DB::table('group_members')
            ->select('user_id', 'group_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'group_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('group_members')
                ->where('user_id', $duplicate->user_id)
                ->where('group_id', $duplicate->group_id)
                ->where('id', '<>', $duplicate->keep_id)
                ->delete();
        }

        if (! Schema::hasIndex('group_members', 'group_members_user_id_group_id_unique')) {
            Schema::table('group_members', function (Blueprint $table) {
                $table->unique(['user_id', 'group_id'], 'group_members_user_id_group_id_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasIndex('group_members', 'group_members_user_id_group_id_unique')) {
            Schema::table('group_members', function (Blueprint $table) {
                $table->dropUnique('group_members_user_id_group_id_unique');
            });
        }

        Schema::table('group_members', function (Blueprint $table) {
            if (Schema::hasColumn('group_members', 'joined_at')) {
                $table->dropColumn('joined_at');
            }

            if (Schema::hasColumn('group_members', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
