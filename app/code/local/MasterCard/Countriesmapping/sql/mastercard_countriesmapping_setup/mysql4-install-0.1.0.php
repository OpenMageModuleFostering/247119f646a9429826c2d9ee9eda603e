<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$installer = $this;

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->run("
DELETE FROM `{$this->getTable('directory_country_region')}` WHERE country_id = 'AU';"); // remove Australia's states
$installer->run("
DELETE FROM `{$this->getTable('directory_country_region')}` WHERE country_id = 'JP';"); // remove Japan's states


$regionfile = dirname(__FILE__) . '/directory_country_region.csv';


$arr=array();
$row = -1;
$i=0;
if (($handle = fopen($regionfile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);

        $row++;
        for ($c = 0; $c < $num; $c++) {
            $arr[$row][$c]= $data[$c];  
           
        }
        
        $i++;
    }
    fclose($handle);
    
    foreach ($arr as $data){
        if(is_numeric($data[1]) && strlen($data[1])<2){
            $code = '0'.$data[1];
        }else{
            $code = $data[1];
        }
        $regionValue = "('".$data[0]."','".$code."','".$data[2]."')";

       $installer->run("INSERT INTO {$this->getTable('directory_country_region')} (country_id, code, default_name) VALUES ". $regionValue . ";");
    }
}


$installer->endSetup();
