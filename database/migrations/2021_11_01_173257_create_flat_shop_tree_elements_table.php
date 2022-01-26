<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlatShopTreeElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS flat_shop_tree_elements");
        DB::statement(<<<EOM
            CREATE VIEW flat_shop_tree_elements AS
            with recursive flat_shop_tree_elements(id, name, tel, parent_id, root_id, depth) as (
                select id, name, tel, parent_id, id, 0
                from shop_tree_elements
                where parent_id is null
                union
                select A1.id, A1.name, A1.tel, A1.parent_id, B1.root_id, B1.depth + 1
                from shop_tree_elements as A1, flat_shop_tree_elements as B1
                where A1.parent_id = B1.id
            )
            select * from flat_shop_tree_elements;
EOM
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS flat_shop_tree_elements");
    }
}
