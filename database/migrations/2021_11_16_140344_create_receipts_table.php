<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("campaign_id")->nullable();
            $table->string("user_id");
            $table->integer("point")->nullable();
            $table->string("type")->nullable();
            $table->string("products")->nullable();
            $table->string("total_price")->nullable();
            $table->string("tel")->nullable();
            $table->string("no")->nullable();
            $table->string("status")->nullable();
            $table->string("pay_date")->nullable();
            $table->string("receipt_path");
            $table->string("mk_status");
            $table->string("company")->nullable();
            $table->string("receipt_value");
            $table->string("meken_value")->nullable();
            $table->string("receipt_str",1000);
            $table->string("receipt_memo",1000)->nullable();
            $table->string("mk_no")->nullable();
            $table->string("mk_tel")->nullable();
            $table->string("mk_date")->nullable();
            $table->string("mk_value")->nullable();
            $table->string("mk_user_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
