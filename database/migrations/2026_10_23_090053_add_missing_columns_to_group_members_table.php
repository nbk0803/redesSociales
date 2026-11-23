<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            if (! Schema::hasColumn('group_members', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('group_members', 'group_id')) {
                $table->foreignId('group_id')->after('user_id')->constrained('groups')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('group_members', 'role')) {
                $table->string('role')->default('member')->after('group_id');
            }

            if (! Schema::hasColumn('group_members', 'registered_by')) {
                $table->foreignId('registered_by')->nullable()->after('role')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            if (Schema::hasColumn('group_members', 'registered_by')) {
                $table->dropForeign(['registered_by']);
                $table->dropColumn('registered_by');
            }

            if (Schema::hasColumn('group_members', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('group_members', 'group_id')) {
                $table->dropForeign(['group_id']);
                $table->dropColumn('group_id');
            }

            if (Schema::hasColumn('group_members', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
