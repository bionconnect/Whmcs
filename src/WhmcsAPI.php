<?php

namespace BionConnection\WhmcsAPI;

use Gufy\Whmcs\WhmcsServiceProvider;

class WhmcsAPI {

    /** @var \Gufy\Whmcs\Whmcs */
    protected $Api;

    
    const STATUS_TERMINATE = "Terminated";

    public function __construct($apiWhmcs) {
        $this->Api = $apiWhmcs;
    }
    public function getClients($idclient){
        
       return $this->Api->execute('GetClientsDetails', array(
                    'clientid' => $idclient
                        )
        );    
    }
    public function validateLogin($email,$pass){
        
        return $this->Api->execute('ValidateLogin', array(
                    'email' => $email,
                    'password2' => $pass
                        )
        ); 
    }
    public function getBillingCycle() {
        return ["Free Account" => 0, "One Time" => 0, "Monthly" => 1, "Quarterly" => 3, "Semi-Annually" => 6, "Annually" => 12, "Biennially" => 24, "Triennially" => 32];
    }

    public function AddCredit($clientid, $amount, $description) {
        return $this->Api->execute('AddCredit', array(
                    'amount' => $amount,
                    'clientid' => $clientid,
                    'description' => $description
                        )
        );
    }
 public function getOrder($ordeid) {
        return $this->Api->execute('GetOrders', array(
                    'id' => $ordeid
                        )
        );
    }
    public function changeService($serverid, $nextduedate,$status) {
        $arrparam = array();
        $arrparam["serviceid"] = $serverid;
        if(isset($nextduedate)){$arrparam["nextduedate"] = $nextduedate; }
        if(isset($status)){$arrparam["status"] = $status; }
         
        return $this->Api->execute('UpdateClientProduct', $arrparam);
    }

 

    public function terminateService($serverid) {

      return  $this->changeService($serverid,null, self::STATUS_TERMINATE)->result == "success" ? true: false;
    }

    public function getProduct($pid = null) {
        $arrParam = array();

        if (isset($pid)) {
            $arrParam["pid"] = $pid;
        }

        return $this->Api->execute('GetProducts', $arrParam);
    }

    public function getServices($serviceid = null, $pid = null, $clientid = null) {
        $arrParam = array();
        if (isset($serviceid)) {
            $arrParam['serviceid'] = $serviceid;
        }
        if (isset($pid)) {
            $arrParam['pid'] = $pid;
        }
        if (isset($clientid)) {
            $arrParam['clientid'] = $clientid;
        }

        return $this->Api->execute('GetClientsProducts', $arrParam);
    }

  public function changeProductCustomField($serviceid, $fiel, $value) {

/*
 $BaseCustom = base64_encode(serialize(array($fiel => $value)));

$stringcutom = base64_encode(serialize( array(
                    'serviceid' => $serviceid,
                    'customfields' => $BaseCustom
        )));



        return $this->Api->execute('UpdateClientProduct',$stringcutom);*/
    }


    public function upgradeProduct($serviceid, $newproductbillingcycle, $newproductid) {
        $methods = $this->getPaymentMethod();
        return $this->Api->execute('UpgradeProduct', array(
                    'serviceid' => $serviceid,
                    'newproductid' => $newproductid,
                    'newproductbillingcycle' => $newproductbillingcycle,
                    'type' => 'product',
                    'paymentmethod' => $methods["paymentmethods"]["paymentmethod"][0]->module
        ));
    }

    public function addOrder($clientid, array $pid, $addonid = null) {
        $methods = $this->getPaymentMethod();

        $arrParam = array(
            'clientid' => $clientid,
            'paymentmethod' => $methods["paymentmethods"]["paymentmethod"][0]->module,
            'pid' => $pid,
        );

        if (isset($addonid)) {
            $arrParam['addonid'] = $addonid;
        }

        return $this->Api->execute('AddOrder', $arrParam
        );
    }

   public function openTicket( $subject, $message, $clientid=null,$name=null,$deptid=1) {
        $arr = array(
                    'deptid' => $deptid,
                    'subject' => $subject,
                    'message' => $message,
                    'clientid' => $clientid,
            );
        
        
        if(isset($name)){ $arr["name"] = $name;}
        if(isset($clientid)){ $arr["clientid"] =$clientid;}
        
        
        return $this->Api->execute('OpenTicket', $arr
                        );
   
    }

    public function getServiceByOrder($ordeid, $clientid, $pid) {
        $arrProduct = array();
        foreach ($this->getServices(null, $pid, $clientid)->products->product as $producto) {
            if ($producto->orderid == $ordeid) {
                $arrProduct[] = $producto;
            }
        }

        return $arrProduct;
    }

    public function acceptOrder($ordeid) {
        return $this->Api->execute('AcceptOrder', array(
                    'orderid' => $ordeid
                        )
        );
    }

    private function getConfigGeneral($fieldConfig) {
        return $this->Api->execute('GetConfigurationValue', array(
                    'setting' => $fieldConfig
                        )
        );
    }

    private function getPaymentMethod() {
        return $this->Api->execute('GetPaymentMethods', array(
                    'setting' => $fieldConfig
                        )
        );
    }

}
