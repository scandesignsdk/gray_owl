Import script
-------------

You will now create a product import to a webshop.

The customer has only delivered some CSV files, but lucky there are headers in the CSV file.

The webshop can handle configurabled products and simple products.

A configurable product contains simple products which can be selected in the webshop, lucky the whole frontend is already done, so you dont need to think of this.

Now its up to you to build the correct import script

Below is a sample csv file the customer have delivered

```csv
SKU,Title,attributesColor,Stock,Price
socks-12,Socks,size:12,5,65
table-blue,Table,color:blue,12,1495
simplesku,Simple Product,,200,75
chair-black,Chair,color:black,20,360
chair-white,Chair,color:white,0,340
socks-18,Socks,size:18,5,65
chair-yellow,Chair,color:yellow,15,360
table-black,Table,color:black,15,1500
shoe-36-black,Black shoe,color:black;size:36,15,1250
shoe-36-white,White shoe,color:white;size:36,10,1250
shoe-38-yellow,Yellow shoe,color:yellow;size:38,8,1250
shoe-38-green,Green shoe,color:green;size:38,5,1250
```

With this you should end up with the following

* 16 products in total which are
    * 12 simple products
        * socks-12
        * table-blue
        * simple-sku
        * chair-black
        * chair-white
        * socks-18
        * chair-yellow
        * table-black
        * shoe-36-black
        * shoe-36-white
        * shoe-38-yellow
        * shoe-38-green
    * 4 configurable products
        * table
            * Which consists of the following products
                * table-blue
                * table-black
        * socks
            * Which consists of the following products
                * socks-12
                * socks-18
        * chair
            * Which consists of the following products
                * chair-black
                * chair-white
                * chair-yellow
        * shoe
            * Which consists of the following products
                * shoe-36-black
                * shoe-36-white
                * shoe-38-yellow
                * shoe-38-green
            
* Configurable products should be build from the product SKU and SKU on the configurable product should be the text before the first hyphen, and the configurable products title should be the simple product title first found.
* Simple products which are in a configurable product must not be visible, but they still need to be added as a simple product
* The configurable price should be the lowest price from the simple products
* If none of the simple products on a configurable product is in stock, then the configurable product is not in stock either.

The parser will run the following

```php
$importer = new SDM\Import($pathToCsv, $csv_demiliter);
$importer->parse();
$products = $importer->getProducts();
```

And the unit tests will be done on the products.

Happy coding!
