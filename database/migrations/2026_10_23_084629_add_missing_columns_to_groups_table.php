<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            if (! Schema::hasColumn('groups', 'registered_by')) {
                $table->foreignId('registered_by')->after('creator_id')->constrained('users');
            }

            if (! Schema::hasColumn('groups', 'status')) {
                $table->string('status')->default('active')->after('registered_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            if (Schema::hasColumn('groups', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('groups', 'registered_by')) {
                $table->dropForeign(['registered_by']);
                $table->dropColumn('registered_by');
            }
        });
    }
};
