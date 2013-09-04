<?php

class Osofflineim_Create_Osofflineim_Settings_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osofflineim_settings', function($table)
        {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('description', 1000)->default('');
            $table->string('type', 20);
            $table->string('default', 255);
            $table->string('value', 255);
            $table->text('options');
            $table->string('validation', 255)->default('');
            $table->string('class')->default('');
            $table->string('section')->default('');
            $table->boolean('is_gui')->default('0');
            $table->string('module_slug', 50)->default('osofflineim');
            $table->integer('module_id')->default('1');
            $table->integer('order')->default('9999');
            $table->timestamps();
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('osofflineim_settings');
    }

}