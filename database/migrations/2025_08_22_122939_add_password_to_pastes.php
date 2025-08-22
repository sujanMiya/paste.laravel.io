<?php

use App\Enums\ProtectedPasteEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pastes', function (Blueprint $table) {
            $table->string('password', 250)->nullable()->after('hash');
            $table->tinyInteger('is_protected')->default(ProtectedPasteEnum::PUBLIC)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pastes', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->dropColumn('is_protected');
        });
    }
};
