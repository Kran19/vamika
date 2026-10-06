<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed existing 11 predefined brand categories for zero downtime and backward compatibility
        $existingCategories = [
            ['name' => 'Durby',      'slug' => 'durby',     'sort_order' => 1],
            ['name' => 'Forolly',    'slug' => 'forolly',   'sort_order' => 2],
            ['name' => 'Million',    'slug' => 'million',   'sort_order' => 3],
            ['name' => "Michi's",    'slug' => 'michi-s',   'sort_order' => 4],
            ['name' => 'Oshon',      'slug' => 'oshon',     'sort_order' => 5],
            ['name' => "Crazzy's",   'slug' => 'crazzy-s',  'sort_order' => 6],
            ['name' => 'Ankit',      'slug' => 'ankit',     'sort_order' => 7],
            ['name' => 'Mayora',     'slug' => 'mayora',    'sort_order' => 8],
            ['name' => 'Confito',    'slug' => 'confito',   'sort_order' => 9],
            ['name' => 'Bakemate',   'slug' => 'bakemate',  'sort_order' => 10],
            ['name' => 'Others',     'slug' => 'others',    'sort_order' => 11],
        ];

        $now = now();
        foreach ($existingCategories as $cat) {
            DB::table('categories')->insert([
                'name'        => $cat['name'],
                'slug'        => $cat['slug'],
                'description' => 'Category for ' . $cat['name'] . ' products',
                'status'      => 'active',
                'sort_order'  => $cat['sort_order'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
