<?php

class Osofflineim_Create_Osofflineim_Messages_Table {

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('osofflineim_messages', function($table)
        {
            $table->string('id', 36);
            $table->string('uuid', 36);
            $table->text('message');
            $table->timestamps();

            $table->index('id');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('osofflineim_messages');
    }
}