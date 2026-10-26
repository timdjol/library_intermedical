<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addColumn('categories', 'parent_id', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->default(0)->index();
        });

        $this->addColumn('posts', 'user_id', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index();
        });
        $this->addColumn('posts', 'status', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->index();
        });
        $this->addColumn('posts', 'deleted_at', function (Blueprint $table) {
            $table->softDeletes();
        });

        if (Schema::hasTable('events') && Schema::hasColumn('events', 'title') && ! Schema::hasColumn('events', 'title_event')) {
            Schema::table('events', function (Blueprint $table) {
                $table->renameColumn('title', 'title_event');
            });
        }

        if (! Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->index();
                $table->string('title');
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('path');
                $table->unsignedTinyInteger('access')->default(0);
                $table->unsignedTinyInteger('status')->default(1)->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('category_document')) {
            Schema::create('category_document', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->index();
                $table->unsignedBigInteger('document_id')->index();
            });
        }

        if (! Schema::hasTable('category_post')) {
            Schema::create('category_post', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->index();
                $table->unsignedBigInteger('post_id')->index();
            });
        }
    }

    public function down(): void
    {
        // Таблицы и колонки могут уже содержать рабочие данные.
    }

    private function addColumn(string $table, string $column, \Closure $callback): void
    {
        if (Schema::hasTable($table) && ! Schema::hasColumn($table, $column)) {
            Schema::table($table, $callback);
        }
    }
};
