<?php
require_once(__DIR__.'/../app/Mage.php');
umask(0);
Mage::app('admin');
echo 'Bulk Import Imgaes script runing at '.date("Y-m-d h:i:sa").' <br/>';
try{
    $csv = new Varien_File_Csv();
    $csvfilepath = __DIR__.'/../media/import/importimage.csv'; 
    $imagepath = __DIR__.'/getImages/';
    $cssfile = fopen($csvfilepath,"r");    
    $data = $csv->getData($csvfilepath);
    echo 'CSV file exsist \n';
    $rows = count($data);
    
    
    for ($j = 1; $j<= $rows; $j++) { 
        $productSKU = $data[$j][0];
        if(!empty($productSKU)){
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$productSKU);
            if (!$product){
                echo $productSKU.' SKU doesn`t exsist \n';
            }else{
            echo $productSKU.' SKU product loaded \n';
            $imagesArray = explode('|', $data[$j][1]);            
            $count = 0;
            $mediaAttribute = array (
                'image',
                'small_image',
                'thumbnail',
                'reference_image',
                'faceimage'
            );
            foreach ($imagesArray as $image){

$imagename=pathinfo($image);
$imagefile=$imagename['filename'].".".$imagename['extension'];
                $imagefile = $imagepath.trim($imagefile);
                file_put_contents($imagefile, file_get_contents(trim($image)));
                if (file_exists($imagefile)) {                            
                    if ($count == 0){
                        $product->addImageToMediaGallery( $imagefile , $mediaAttribute, false, false );
                    }else{
                        $product->addImageToMediaGallery( $imagefile , null, false, false );
                    }
                    $product->setStoreId(0);
                    echo $image.' Image uploaded \n';
                } else {
                    echo $image.' Image not found \n';
                }
                $count++;
            }
            $product->save(); 
            echo $productSKU.' SKU uploaded successfully \n';
        }
        }
    }
    fclose($cssfile);
    echo 'Uploaded Successfully at '.date("Y-m-d h:i:sa").' \n';
}  catch (Exception $e){
    echo $e->getMessage();
}   