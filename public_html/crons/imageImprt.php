<?php
require_once(__DIR__.'/../app/Mage.php');
umask(0);
Mage::app('admin');
echo 'Bulk Import Imgaes script runing at '.date("Y-m-d h:i:sa").' <br/>';
try{
    $csv = new Varien_File_Csv();
    //$csvfilepath = trim(Mage::getBaseDir().Mage::getStoreConfig('bulkimageupload/bulkimageuploadconfig/csvfilepath'));
    $csvfilepath = 'importimage.csv';
   // $csvfilepath ='/tmp/importimage.csv';
     //$imagepath = trim(Mage::getBaseDir().Mage::getStoreConfig('bulkimageupload/bulkimageuploadconfig/imagespath'));
    $imagepath = 'getImages/';
    //$imagepath = '/tmp/';
    $cssfile = fopen($csvfilepath,"r");
    $data = $csv->getData($csvfilepath);
    //Mage::log('CSV file exsist', null, 'bulkimagesimport.log');
    echo 'CSV file exsist <br/>';
    $rows = count($data);

    //$filename='/apps_media/catalog_exclusively/shell/imageUploadReport.txt';
    unlink($filename);
    for ($j = 1; $j<= $rows; $j++) {
        $productSKU = $data[$j][0];
        if(!empty($productSKU)){
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$productSKU);
            if (!$product){
                //file_put_contents($filename,$productSKU.' --> SKU doesn`t exsist');
                echo $productSKU.' SKU doesn`t exsist \n';
            }else{
            //Mage::log($productSKU.' SKU product loaded', null, 'bulkimagesimport.log');
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
                $filepath=pathinfo($image);
                $filepath=$imagepath.$filepath['basename'];
                file_put_contents($filepath,file_get_contents(trim($image)));
                $imagefile = $filepath;
                if (file_exists($imagefile)) {
                    if ($count == 0){
                        $product->addImageToMediaGallery( $imagefile , $mediaAttribute, false, false );
                    }else{
                        $product->addImageToMediaGallery( $imagefile , null, false, false );
                    }
                    $product->setStoreId(0);
                    //Mage::log($imagefile.' Image uploaded', null, 'bulkimagesimport.log');
                    echo $image.' Image uploaded \n';
                } else {
                   //file_put_contents($filename,$image.' --> Image not found');
                    //Mage::log($imagefile.' Image not found', null, 'bulkimagesimport.log');
                    echo $image.' Image not found \n';
                }
                $count++;
            }
            $product->save();
            //Mage::log($productSKU.' SKU uploaded successfully', null, 'bulkimagesimport.log');
            echo $productSKU.' SKU uploaded successfully \n';
        }
        }
    }
    fclose($cssfile);
    echo 'Uploaded Successfully at '.date("Y-m-d h:i:sa").' <br/>';
}  catch (Exception $e){
    //Mage::log($e->getMessage(), null, 'bulkimagesimport.log');
    echo "Exception".$e->getMessage();
}

