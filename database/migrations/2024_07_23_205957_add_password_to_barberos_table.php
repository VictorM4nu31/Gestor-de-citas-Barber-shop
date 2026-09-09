<?php

use Illuminate\Database\Migrations\Migration;

// Migration left intentionally empty because password and user_id are
// now created in the original create_barberos_table migration.
class AddPasswordToBarberosTable extends Migration
{
    public function up()
    {
        // noop
    }

    public function down()
    {
        // noop
    }
}
