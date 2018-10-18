<?php

namespace BionConnection\WhmcsAPI;

use Gufy\Whmcs\WhmcsServiceProvider;

class WhmcsAPI {

    /** @var \Gufy\Whmcs\Whmcs */
    protected $Api;

    const STATUS_TERMINATE = "TERMINATE";

    public function __construct($apiWhmcs) {
        $this->Api = $apiWhmcs;
    }

    public function AddCredit($clientid, $amount, $description) {
        return $this->Api->execute('AddCredit', array(
                    'amount' => $amount,
                    'clientid' => $clientid,
                    'description' => $description
                        )
        );
    }

    public function changeServiceDueDate($serverid, $nextduedate) {
        return $this->Api->execute('UpdateClientProduct', array(
                    'serviceid' => $serverid,
                    'nextduedate' => $nextduedate
                        )
        );
    }

    private function changeServiceStatus($serverid, $status) {

        return $this->Api->execute('UpdateClientProduct', array(
                    'serviceid' => $serverid,
                    'status' => $status
        ));
    }

    public function terminateService($serverid) {

        $this->changeServiceStatus($serverid, STATUS_TERMINATE);
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

    public function changeProduct($serviceid, $newproductbillingcycle = null, $newproductid = null, $icc = null) {
        // No quedo clara  
    }

    public function addOrder($clientid, array $pid, $addonid = null) {

        $arrParam = array(
            'clientid' => $clientid,
            'paymentmethod' => 'gmp',
            'pid' => $pid,
        );

        if (isset($addonid)) {
            $arrParam['addonid'] = $addonid;
        }

        return $this->Api->execute('AddOrder', array(
                    'clientid' => $clientid,
                    'paymentmethod' => 'gmp',
                    'pid' => $pid
        ));
    }

    public function openTicket($deptid, $subject, $message, $clientid) {
        return $this->Api->execute('OpenTicket', array(
                    'deptid' => $deptid,
                    'subject' => $subject,
                    'message' => $message,
                    'clientid' => $clientid,
                        )
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
    public function acceptOrder($ordeid){
       return $this->Api->execute('AcceptOrder', array(
                    'orderid' => $ordeid
                        )
        );  
    }

}
