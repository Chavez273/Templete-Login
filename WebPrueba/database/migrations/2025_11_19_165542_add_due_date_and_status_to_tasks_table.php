<?php
// database/migrations/2025_01_19_000000_add_due_date_and_status_to_tasks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('description');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending')->after('due_date');
            // Cambiar 'urgencia' a 'urgency' para consistencia, o mantener ambos
            $table->renameColumn('urgencia', 'urgency');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'status']);
            $table->renameColumn('urgency', 'urgencia');
        });
    }
};
