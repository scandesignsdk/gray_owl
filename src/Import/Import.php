<?php
namespace SDM\Import;

class Import implements ImportInterface
{
    
    private $filePath;    
    private $delimiter;
    private $products;
    private $header;
    
    /**
     * Importer
     *
     * @param string $filePath The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, string $delimiter = ',')
    {
        $this->filePath = $filePath;
        $this->delimiter = $delimiter; 
    }
    
    /**
     * Parse the csv file
     *
     * @return void
     */
    public function parse(): void{
        
        //Opens and reads the file
        $file = fopen($this->filePath, "r");
        //Gets the data from the CSV file, seperated by (,)
        
        //First get headers (Not used)
        $header = fgetcsv($file, 1000, $this->delimiter);
        
        //Get rest of the files (Products)
        while (($row = fgetcsv($file, 1000, $this->delimiter)) !== FALSE){
            
            //Fetch product attributes by splitting the string at ';' and ':'
            //and then adding each new string to $attributes array
            $attributes = preg_split("/(;|:)/", $row[2]);
            
            //Remove every second value of array
            $i = 1;
            foreach($attributes as $key => $value){
                if($i % 2 == 0){
                    
                    unset($attributes[$key]);    
                }
                $i++;
            }  
            //Rearranges array values and check if empty
            $attributes = array_values($attributes);
            if(empty($attributes[0])){
                $attributes = null;
            }
            
            //See if simple product is in stock
            if($row[3] > 0){
                $inStock = true; //Is in stock
            } else {
                $inStock = false; //Is not in stock
            }
            
            //Create SimpleProduct
            $product = new SimpleProduct($row[0], $row[1], $attributes, $row[3], $row[4], $inStock, true);
            
            
            //Add SimpleProduct to $products array
            $this->products[] = $product;
        }
        
        //Check if configurable products needs to be created
        $this->findConfigurableProducts();
        
    }

    /**
     * Check if configurable products needs to be created
     *
     * @return void
     */
    private function findConfigurableProducts(){
        
        //Create new array of configurable products
        $configArray = array();
        
        //Go through simple products
        foreach($this->products as $product){
            
            //Check if '-' is part of the product SKU
            //Then create new SKU for configurable product
            $productSku = $product->getSku();
            if(preg_match('/-/', $productSku)){    
                $configSku = strtok($productSku, '-');
                
                //Check if configurable SKU is already in array
                if(strpos($productSku, $configSku) !== false){
                    
                    //Set the simple product to non visible
                    $product->setIsVisible(false);
                    
                    //Add the simple product to the right configurable product
                    $configArray[$configSku][] = $product;
                }
            }
        }
        
        //Traverse config array and create new ConfigurableProduct
        foreach($configArray as $sku => $products){
            $configProduct = new ConfigurableProduct($sku);
            
            //Add each SimpleProduct to the ConfigurableProduct
            foreach($products as $product){
                
                $configProduct->addSimpleProduct($product);
            }
            
            //Add the ConfigurableProduct to the Product array
            $this->products[] = $configProduct;
        } 
    }

    /**
     * Get products imported
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array{
        
        return $this->products; 
    }

    /**
     * Add product to the importer
     *
     * @param ProductInterface $product
     * @return Import
     */
    public function addProduct(ProductInterface $product): Import{
        
        $this->products[] = $product; 
        
        return $this;
    }

}
