<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMetaFieldsFromSeveralTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            // $table->dropColumn('meta-title');
            // $table->dropColumn('meta-description');
            // $table->dropColumn('meta-keywords');

            // $table->text('meta_og_title')->nullable();
            // $table->text('meta_og_image')->nullable();
            // $table->text('meta_og_description')->nullable();
        });

        Schema::table('resources', function (Blueprint $table) {
            // $table->renameColumn('meta-title', 'meta_title');
            // $table->renameColumn('meta-description', 'meta_description');
            // $table->renameColumn('meta-keywords', 'meta_keywords');

            // $table->text('meta_og_title')->nullable();
            // $table->text('meta_og_image')->nullable();
            // $table->text('meta_og_description')->nullable();
        });

        Schema::table('cm_categories', function (Blueprint $table) {
            
            // $table->text('meta_title')->nullable();
            // $table->text('meta_description')->nullable();
            // $table->text('meta_keywords')->nullable();

            // $table->text('meta_og_title')->nullable();
            // $table->text('meta_og_image')->nullable();
            // $table->text('meta_og_description')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            // $table->renameColumn('meta_title', 'meta-title');
            // $table->renameColumn('meta_description', 'meta-description');
            // $table->renameColumn('meta_keywords', 'meta-keywords');

            // $table->dropColumn('meta_og_title');
            // $table->dropColumn('meta_og_image');
            // $table->dropColumn('meta_og_description');
        });


        Schema::table('resources', function (Blueprint $table) {
            // $table->renameColumn('meta-title', 'meta_title');
            // $table->renameColumn('meta-description', 'meta_description');
            // $table->renameColumn('meta-keywords', 'meta_keywords');

            // $table->text('meta_og_title')->nullable();
            // $table->text('meta_og_image')->nullable();
            // $table->text('meta_og_description')->nullable();
        });


        Schema::table('cm_categories', function (Blueprint $table) {
            
            // $table->dropColumn('meta_title');
            // $table->dropColumn('meta_description');
            // $table->dropColumn('meta_keywords');

            // $table->dropColumn('meta_og_title');
            // $table->dropColumn('meta_og_image');
            // $table->dropColumn('meta_og_description');

        });
    }
}
