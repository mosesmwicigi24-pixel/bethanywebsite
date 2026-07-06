<?php

class Lnm_reconciliation_model extends CI_Model {

	function reconcile_lnm_v1_contacts() {
		$this->db->select("*");
		$this->db->from('paybill_payments');
		$this->db->where(array('phone_number' => ''));
		$paybill_payments = $this->db->get()->result();

		foreach ($paybill_payments as $row) {

			if (preg_match('/^(254|0)[1-9]\d{8}$/', $row->MSISDN) == 1) {

				$data = array(
	                'phone_number' => '+' . $row->MSISDN
	            ); 
	            $this->db->where( array('paybill_payment_id' => $row->paybill_payment_id));
	            $this->db->update('paybill_payments', $data);	            
			}
		}

		$arr_return = array('res' => true,'dt' => 'Reconciliation successful');
		return $arr_return;
	}

	function reconcile_lnm_v2_contacts($transaction_id,$phone_number) {

		$data = array(
            'phone_number' => '+' . $phone_number
        ); 
        $this->db->where( array('transaction_id' => $transaction_id));
        $this->db->update('paybill_payments', $data);
        
	}


}