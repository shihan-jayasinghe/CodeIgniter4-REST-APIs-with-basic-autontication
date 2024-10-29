<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{
   protected $modelName="App\Models\ProductModel";
   protected $format="json";

   //post
   public function addProduct()
   { 
      $validationRules=[
        "title"=>[
            "rules"=>"required|min_length[3]",
            "errors"=>[
                "required"=>"product title is required",
                "min_length"=>"title must be greater than 3 characters"
            ]
        ],
        "cost"=>[
            "rules"=>"required|integer|greater_than[0]",
            "errors"=>[
                "required"=>"product cost is required",
                "integer"=>"cost must be an integer value",
                "greater_than"=>"product cost must be greater than 0"
            ]
        ]
      ];

      if(!$this->validate($validationRules))
      {
        
        return $this->fail($this->validator->getErrors());
      }

      $imageFile=$this->request->getFile("product_image");
      $productImageURL="";
      
      if($imageFile)
      {
        $newProductImageName=$imageFile->getRandomName();
        $imageFile->move(FCPATH."uploads",$newProductImageName);
        $productImageURL="uploads/".$newProductImageName;
      }

      $data=$this->request->getPost();
      $title=$data["title"];
      $cost=$data["cost"];
      $desc=isset($data["description"]) ? $data["description"] : "";

      if($this->model->insert([
        "title"=>$title,
        "cost"=>$cost,
        "description"=>$desc,
        "product_Image"=>$productImageURL
        ]))
      {
        return $this->respond([
            "status"=>true,
            "message"=>"product added successfully"
        ]);
      }
      else
      {
        return $this->respond([
            "status"=>false,
            "message"=>"failed to add product"
        ]);
      }
   }

   //get
   public function listAllProduct()
   {
      $products=$this->model->findAll();
      if(!empty($products))
      {
        return $this->respond([
          "status"=>true,
          "message"=>"product found",
          "products"=>$products
        ]);
      }
      else{
        return $this->respond([
            "status"=>false,
            "message"=>"product not found"
        ]);
      }
   }

   //get
   public function productData($productId)
   {
      $data=$this->model->find($productId);
      if(!empty($data))
      {
        return $this->respond([
            "status"=>true,
            "message"=>"product found",
            "product"=>$data
        ]);
      }
      else{
          return $this->respond([
              "status"=>false,
              "message"=>"no product found related to the product Id",
          ]);
      }
   }

   //put
   public function updateProduct($productId)
   {
      $product=$this->model->find($productId);
      if($product)
      {
        //$updated_data=json_decode(file_get_contents("php://input"),true);
        $updated_data['title']=$this->request->getVar("title");
        $updated_data['cost']=$this->request->getVar("cost");
        $updated_data['description']=$this->request->getVar("description");

        $fileObj=$this->request->getFile("product_image");
        $imageURL=$product["product_Image"];

        if($fileObj)
        {
          $newImageName=$fileObj->getRandomName();
          $fileObj->move(FCPATH."uploads",$newImageName);
          $imageURL="uploads/".$newImageName;
        }

        $title= isset($updated_data['title']) ? $updated_data['title'] : $product["title"];

        $cost=isset($updated_data['cost']) ? $updated_data['cost'] : $product["cost"];

        $desc=isset($updated_data['description']) ? $updated_data['description'] : $product["description"];

       

            if($this->model->update($productId,[
            "title"=>$title,
            "cost"=>$cost,
            "description"=>$desc,
            "product_Image"=>$imageURL
            ]))
            {
              return $this->respond([
                  "status"=>true,
                  "message"=>"product is updated"
              ]);
            }
            else
            {
              return $this->respond([
                "status"=>false,
                "message"=>"product update failed"
              ]);
            }
      }
      else
      {
        return $this->respond([
            "status"=>false,
            "message"=>"product not found"
        ]);
      }
   }

   //delete
   public function deleteProduct($productId)
   {
      $productData=$this->model->find($productId);
      if($productData)
      {
          if($this->model->delete($productId))
          {
            return $this->respond([
                "status"=>true,
                "message"=>"product deleted successfully"
            ]);
          }
      }
      else
      {
        return $this->respond([
            "status"=>false,
            "message"=>"cannot fid the product with this product id"
        ]);
      }
   }


}
