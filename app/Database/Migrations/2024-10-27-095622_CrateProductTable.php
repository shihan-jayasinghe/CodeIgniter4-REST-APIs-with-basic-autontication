<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrateProductTable extends Migration
{
    public function up()
    {
       $this->forge->addField([
          "id"=>[
            "type"=>"int",
            "auto_increment"=>true,
            "null"=>false,
            "unsigned"=>true
          ],
          "title"=>[
            "type"=>"varchar",
            "constraint"=>120,
            "null"=>false
          ],
          "cost"=>[
             "type"=>"int",
             "null"=>false
          ],
          "description"=>[
             "type"=>"text",
             "null"=>true
          ],
          "product_Image"=>[
             "type"=>"text",
             "null"=>true
          ],
          "created_at datetime default current_timestamp"
       ]);

       $this->forge->addPrimaryKey("id");
       $this->forge->createTable("products");
    }

    public function down()
    {
       $this->forge->dropTable("products");
    }
}
