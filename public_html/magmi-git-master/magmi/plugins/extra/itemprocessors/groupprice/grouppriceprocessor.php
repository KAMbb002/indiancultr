<?php

/**
 * Import group prices for columns names called "group_price:"
 */
class GrouppriceProcessor extends Magmi_ItemProcessor
{
    protected $_groups = array();
    protected $_singleStore;
    protected $_priceScope;
	protected $_tax_class_id;
    public function getPluginInfo()
    {
        return array('name'=>'Group Price Importer','author'=>'Tim Bezhashvyly,dweeves','version'=>'0.0.3');
    }

    
    public function processItemAfterId(&$item, $params = null)
    {
        $table_name = $this->tablename("catalog_product_entity_group_price");
        $group_cols = array_intersect(array_keys($this->_groups), array_keys($item));
        
        if (!empty($group_cols))
        {
            $website_ids = $this->_singleStore && $this->_priceScope ? $this->getItemWebsites($item) : array(0);
            $group_ids = array();
            foreach ($group_cols as $key)
            {
                if ($this->_groups[$key]['id'])
                {
                    $group_ids[] = $this->_groups[$key]['id'];
                }
            }
            
            if (!empty($group_ids))
            {
                $sql = 'DELETE FROM ' . $table_name . '
                              WHERE entity_id=?
                                AND customer_group_id IN (' . implode(', ', $group_ids) . ')
                                AND website_id IN (' . implode(', ', $website_ids) . ')';
                $this->delete($sql, array($params['product_id']));
            }

            $sql = 'INSERT INTO ' . $table_name .
            ' (entity_id, all_groups, customer_group_id, value, website_id) VALUES ';
            
            foreach ($group_cols as $key)
            {            	
            	$price=str_replace(",",".",$item[$key]);
                if (!empty($price))
                {
                    $group_id = $this->_groups[$key]['id'];
                    $inserts = array();
                    $data = array();
                    
                    foreach ($website_ids as $website_id)
                    {
                        $inserts[] = '(?,?,?,?,?)';
                        $data[] = $params['product_id'];
                        $data[] = 0;
                        $data[] = $group_id;
                        $data[] = $price;
                        $data[] = $website_id;
                    }                    
                }
            }
            //multiple insert
            if (!empty($data))
            {
            	$sql .= implode(', ', $inserts);
            	$sql .= ' ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)';
            	$this->insert($sql, $data);
            }
            
        }
        return true;
    }

    public function createGroup($groupname)
	{
		$cg=$this->tablename('customer_group');
    	$sql="INSERT INTO $cg (customer_group_code,tax_class_id)
    				VALUES (?,?)";
		$gid=$this->insert($sql,array($groupname,$this->_tax_class_id));    	
    	return $gid;
    		
    }
    
    /**
     * Inspect column list for group price columns info
     *
     * @param
     *            $cols
     * @param null $params            
     * @return bool
     */
    public function processColumnList(&$cols, $params = null)
    {
        foreach ($cols as $col)
        {
            if (preg_match("|group_price:(.*)|", $col, $matches))
            {
            	$groupname=$matches[1];
                $sql = 'SELECT customer_group_id FROM ' . $this->tablename("customer_group") .
                     ' WHERE customer_group_code = ?';
                if ($id = $this->selectone($sql, $groupname, "customer_group_id"))
                {
                    $this->_groups[$col] = array('name'=>$groupname,'id'=>$id);
                }
                else
                {
                   	$this->_groups[$col] = array('name'=>$groupname,'id'=>$this->createGroup($groupname));
                }
            }
        }
        
        return true;
    }

    public function initialize($params)
    {
        $sql = 'SELECT COUNT(store_id) as cnt FROM ' . $this->tablename('core_store') . ' WHERE store_id != 0';
        $ns = $this->selectOne($sql, array(), "cnt");
        $this->_singleStore = $ns == 1;
        
        /* Check price scope in a general config (0 = global, 1 = website) */
        $sql = 'SELECT value FROM ' . $this->tablename('core_config_data') . ' WHERE path = ?';
        $this->_priceScope = intval($this->selectone($sql, array('catalog/price/scope'), 'value'));
        /* Getting customer tax class */
        $sql="SELECT class_id FROM tax_class WHERE class_type='CUSTOMER'";
        $this->_tax_class_id=$this->selectone($sql,null,'class_id');
        
    }
}
