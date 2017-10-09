<?php
namespace SDM\Import;

class Import implements ImportInterface
{
    
    private $filePath;    
    private $delimiter;
    private $header;
    private $configurable;
    private $simples;
    private $visibles;
    private $nonvisibles;
    private $products;
    private $productCount;
    
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
        $header = fgetcsv($file, 1000, $this->delimiter);
        
        while (($row = fgetcsv($file, 1000, $this->delimiter)) !== FALSE){
            
            //Fetch product attributes
            $attributes = preg_split("/(;|:)/", $row[2]);
            
            //Remove every second value of array
            $i = 1;
            foreach($attributes as $key => $value){
                if($i % 2 == 0){
                    
                    unset($attributes[$key]);    
                }
                $i++;
            }  
            //Rearanges array values
            $attributes = array_values($attributes);
            
            if(empty($attributes[0])){
                $attributes = null;
            }
            
            //See if simple product is in stock
            if($row[3] > 0){
                $inStock = true;
            } else {
                $inStock = false;
            }
            
            //Create SimpleProduct
            $simpleProduct = new SimpleProduct($row[0], $row[1], $attributes, $row[3], $row[4], $inStock, true);
            
            
            //Add SimpleProduct to $products array
            $products[] = $simpleProduct;
        }
        
        $configArray = array();
        $tempArray = array();
        $skuArray = array();
        $sku = "";
        $title = "";
        $attributes = array();
        $inStock = true;
        $price = 0;
        $visible = true;
        $foundConfig = false;
        foreach($products as $product){
            $fullString = $product->getSku();
            
            if(preg_match('/-/', $fullString)){
                
                $foundConfig = true;
                
                $newSKU = strtok($fullString, '-');
                
                
                if(strpos($fullString, $newSKU) !== false){
                    
                    //If new, create new configArray
                    if(!array_key_exists($newSKU, $configArray)){
                        $configArray[$newSKU] = array();
                        
                        $skuArray[$newSKU] = array(1);
                        print_r("added" . $newSKU);
                        $tempArray[] = $newSKU;
                        $tempArray[] = $product->getTitle();;
                        $tempArray[] = $product->getAttributes();;
                        $tempArray[] = $product->isInStock();
                        $tempArray[] = $product->getPrice();
                        $tempArray[] = $product->isVisible();                
                    
                    } else{
                        $skuArray[$newSKU][0]++;                             
                    }
                    
                
                    //Check if a smaller price is available
                    if($product->getPrice() < $tempArray[4]){

                        $tempArray[] = $product->getPrice();
                        //$price = $product->getPrice();
                    }
                    
                    //Set the simple product to non visible
                    $product->setIsVisible(false);
                    
                                    
                }                
            }
        }
        if($foundConfig){
            
            $amount = (count($tempArray) / 6);
            
            if($amount > 1){
                
                $h = 0;
                
                while($h != $amount){
                    $tempArrays = array_chunk($tempArray, 6);
                    //print_r($tempArrays);
                    
                    $configProduct = new ConfigurableProduct($tempArrays[$h][0], $tempArrays[$h][1], $tempArrays[$h][2], $tempArrays[$h][3], $tempArrays[$h][4], $tempArrays[$h][5]);
                    
                    
                    //TESTING
                    $configProduct->addSimpleProduct($product);
                    $configProduct->addSimpleProduct($product);;
                    
                    $products[] = $configProduct;

                    $h++;
                } 
   
            } else {
                
                $configProduct = new ConfigurableProduct($tempArray[0], $tempArray[1], $tempArray[2], $tempArray[3], $tempArray[4], $tempArray[5]);
                
                $products[] = $configProduct;
                
            }
        }
            
        $this->products = $products;
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
        
        $this->product = $product; 
        
        return $product;
    }

}
