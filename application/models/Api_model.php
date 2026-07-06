<?php
class Api_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->model('checkout_model');
    }


    //MPESA
	function get_mpesa_settings(){
        $this->db->from('mpesa_settings');
        return $this->db->get()->result();
    }

    function confirmation($callback_data){
        $insert = $this->db->insert('paybill_payments', $callback_data);
        $insert_id = $this->db->insert_id();

        if ($insert){
			//UPDATE PHONE NUMBER
            $this->db->from('paybill_payments');
            $this->db->where(array('MSISDN' => $callback_data['MSISDN']));
            $this->db->where(array('phone_number !=' => ''));
            $paybill_payment = $this->db->get()->result();

            $phone_number = '';
            foreach ($paybill_payment as $row) {
                $phone_number = $row->phone_number;
            }
            $data = array(
                'phone_number' => $phone_number
            );
            $this->db->where(array('paybill_payment_id' => $insert_id));
            $this->db->update('paybill_payments', $data);
		}else{
			return false;
		}        
    }

    function update_contact($transaction_id,$phone_number) {

        //PAYBILL PAYMENTS
        $data = array(
            'phone_number' => $phone_number
        );
        $this->db->where(array('paybill_payment_id' => $transaction_id));
        $this->db->update('paybill_payments', $data);

    }


    //PESAPAL
    function get_pesapal_settings(){
        $this->db->from('pesapal_settings');
        return $this->db->get()->result();
    }

    function submit_pesapal_payment($data, $transaction_tracking_id) {

        $this->db->where( array('transaction_tracking_id' => $transaction_tracking_id));
        $query = $this->db->get('pesapal_payments');

        if ($query->num_rows() > 0){
            return true;
        }else{
            if ($this->db->insert('pesapal_payments', $data)){
                return true;
            }else{
                return false;
            }
        }        
    }
	
}