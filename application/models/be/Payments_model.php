<?php
class Payments_model extends CI_Model {

	function __construct(){
		parent::__construct();		
		$this->load->model('be/sales_model');
		$this->load->model('pos/main_model');
	}
	
	//PAYBILL PAYMENTS
	function get_paybill_payments(){
		$paybill_payment_status = $this->input->post('paybill_payment_status');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

		$this->db->select("paybill_payments.*");
		$this->db->from('paybill_payments');
		//$this->db->join('customers', 'customers.customer_id = order_summary.ord_customer_id', 'left outer');

		if ($paybill_payment_status != ''){
      		$this->db->where( array('paybill_payments.transaction_completed' => $paybill_payment_status));
        }

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(paybill_payments.transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(paybill_payments.transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

		//$this->db->where( array('order_summary.is_deleted'=>0));
		return $this->db->get()->result();
	}

	function get_paybill_payment($paybill_payment_id){
		$this->db->select("paybill_payments.*");
		$this->db->from('paybill_payments');

		$this->db->where( array('paybill_payment_id' => $paybill_payment_id));
		return $this->db->get()->result();
	}

	function get_assign_paybill_payment_transactions(){

		$transaction_type = $this->input->post('transaction_type');

		$result = array();

		if ($transaction_type == 'Online Order') {

			$this->db->select("order_summary.*, customers.customer_id, customers.first_name, customers.last_name, customers.phone_number, customers.email_address");
			$this->db->from('order_summary');
			$this->db->join('customers', 'customers.customer_id = order_summary.ord_customer_id', 'left outer');

			$this->db->where( array('order_summary.ord_order_status' => 0));

			$result = $this->db->get()->result();

		} elseif ($transaction_type == 'Pos Order') {

			$this->db->select("ps.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
	        $this->db->from('pos_sales ps');     
	        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');  
	        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer');  

	        $this->db->where( array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1));
	        $this->db->where('total_paid < total_sale');
	        $this->db->order_by("ps.pos_sale_id", "desc");
	        
	        $result = $this->db->get()->result();
		}

		return $result;
	}

	function submit_paybill_payment_assign_transaction(){

		$transaction_type = $this->input->post('transaction_type');
		$transaction_id = $this->input->post('transaction_id');
		$paybill_payment_id = $this->input->post('paybill_payment_id');
		
		//$system_user_id = $this->input->post('system_user_id');

		//$charges_settings = $this->get_charges_settings();

		$arr_return = array('status' => false, 'message' => '', 'status_code' => 1);

		if ($transaction_type == 'Online Order') {

			$order_total = 0;

			$online_order = $this->sales_model->get_online_order($transaction_id);

			foreach ($online_order as $row) {
				$order_total = (double)$row->ord_total;
			}

			$amount_paid = 0;
			$this->db->from('paybill_payments');
        	$this->db->where( array('bill_reference_number'=>$transaction_id, 'transaction_completed'=>0, 'paybill_payment_id !='=>$paybill_payment_id));
        	$payments =  $this->db->get()->result();

        	foreach($payments as $row){
                $amount_paid = $amount_paid + $row->transaction_amount;
            }

            $payment_amount = 0;
            $paybill_payment = $this->get_paybill_payment($paybill_payment_id);

            foreach ($paybill_payment as $row) {
            	$payment_amount = $payment_amount + $row->transaction_amount;
            }

            $total_amount_paid = $amount_paid + $payment_amount;

            if ($total_amount_paid < $order_total) {

            	//UPDATE PAYMENTS TABLE
            	$data = array(
					'bill_reference_number' => $transaction_id
				);
				$this->db->where(array('paybill_payment_id' => $paybill_payment_id));
				$this->db->update('paybill_payments', $data);

				$arr_return = array('status' => true,'message' => 'Payment assigned to Online Order successfully. The amount was however not sufficient to complete the transaction','status_code' => 1);

            } elseif ($total_amount_paid == $order_total) {
            	//UPDATE PAYMENTS TABLE
            	$data = array(
					'bill_reference_number' => $transaction_id
				);
				$this->db->where(array('paybill_payment_id' => $paybill_payment_id));
				$this->db->update('paybill_payments', $data);

				//UPDATE ORDER SUMMARY
		        $data = array(
		            'ord_payment_method' => 'Mpesa',
		            'ord_order_status' => 1
		        );
		        $this->db->where(array('ord_order_number'=> $transaction_id));
		        $this->db->update('order_summary', $data);

		        $order = $this->sales_model->get_online_order($transaction_id);
		        foreach($order as $row){
		            $customer_id = $row->ord_customer_id;
		            $affiliate_code = $row->ord_affiliate_code;
		            $order_amount = $row->ord_total;
		            $affiliate_click_id = $row->ord_affiliate_click_id;
		        }

		        $data = array(
		            'ord_order_number' => $transaction_id,
		            'customer_id' => $customer_id,
		            'transaction_completed' => 1
		        );
		        $this->db->where(array('bill_reference_number'=>$order_number));
		        $this->db->update('paybill_payments', $data);

		        //AFFILIATE REFERRAL
		        if ($affiliate_code != '') {
		            $affiliate = $this->affiliates_model->get_affiliate_by_code($affiliate_code);
		            foreach ($affiliate as $row) {
		                $affiliate_id = $row->affiliate_id;
		                $affiliate_package_id = $row->affiliate_package_id;
		                $commission = $row->commission;
		                $total_commissions = $row->total_commissions;
		                $commissions_balance = $row->commissions_balance;
		            }

		            $commission_amount = (((float)$commission/100) * (float)$order_amount);
		            $commissions_balance = $commissions_balance + $commission_amount;

		            //AFFILIATE REFERRALS TABLE
		            $data = array(
		                'affiliate_id' => $affiliate_id,
		                'ord_order_number' => $transaction_id,
		                'order_amount' => $order_amount,
		                'affiliate_package_id' => $affiliate_package_id,
		                'commission' => $commission,
		                'commission_amount' => $commission_amount,
		                'commissions_balance' => $commissions_balance,
		                'affiliate_click_id' => $affiliate_click_id
		            );  

		            $this->db->insert('affiliate_referrals', $data);

		            //AFFILIATES TABLE
		            $data = array(
		                'total_commissions' => $total_commissions + $commission_amount,
		                'commissions_balance' => $commissions_balance + $commission_amount
		            ); 
		            $this->db->where( array('affiliate_code' => $affiliate_code));
		            $this->db->update('affiliates', $data);
		        }
		        $arr_return = array('status' => true,'message' => 'Payment assigned to Online Order and transaction completed successfully.','status_code' => 1);
            } elseif ($total_amount_paid > $order_total) {
            	$arr_return = array('status' => false,'message' => 'Assigning this Payment will result in an overpayment. <br/><br/>Required Amount: ' . number_format((float)$order_total, 2) . '<br/>Payment Amount: ' . number_format((float)$total_amount_paid, 2) . '<br/><br/>Do you wish to proceed in completing this transaction and set the excess amount as change?','status_code' => 2);
            }
		} elseif ($transaction_type == 'Pos Order') {

			$sale_total_paid = 0;
			$total_paid = 0;
	        $this->db->select("*");
	        $this->db->from('pos_payments');       
	        $this->db->where( array('pos_sale_id' => $transaction_id, 'is_void' => 0));
	        $pos_payments = $this->db->get()->result();

	        foreach ($pos_payments as $row) {
	            $total_paid = $total_paid + $row->payment_amount;
	        }

	        $sale_total = 0;
	        $pos_sale_number = '';
	        $this->db->select("*");
	        $this->db->from('pos_sales');       
	        $this->db->where( array('pos_sale_id' => $transaction_id));
	        $pos_sale = $this->db->get()->result();

	        foreach ($pos_sale as $row) {
	            $sale_total = $row->total_sale;
	            $pos_sale_number = $row->pos_sale_number;
	        }

	        $payment_amount = 0;
            $paybill_payment = $this->get_paybill_payment($paybill_payment_id);

            foreach ($paybill_payment as $row) {
            	$payment_amount = $payment_amount + $row->transaction_amount;
            }

	        $sale_total_paid = $total_paid + $payment_amount;

	        if ($sale_total_paid <= $sale_total) {

	        	//UPDATE PAYMENTS TABLE
            	$data = array(
					'bill_reference_number' => $pos_sale_number
				);
				$this->db->where(array('paybill_payment_id' => $paybill_payment_id));
				$this->db->update('paybill_payments', $data);

				//ADD POS PAYMENT
	        	$data = array(
                    'pos_sale_id' => $transaction_id,
                    'payment_amount' => $payment_amount,
                    'payment_method' => 'MPESA',
                    'reference_number' => '',
                    'payment_note' => '',
                    'paybill_payment_id' => $paybill_payment_id,
                    'system_user_id' => $this->session->userdata('system_user_id')
                );
                $res = $this->db->insert('pos_payments', $data);
                $pos_payment_id = $this->db->insert_id();

                //UPDATE PAYBILL PAYMENTS TABLE
                if ($res) {
                    $data = array(
                        'pos_sale_id' => $transaction_id,
                        'transaction_completed' => 1
                    );
                    $this->db->where( array('paybill_payment_id' => $paybill_payment_id));
                    $this->db->update('paybill_payments', $data);
                }

                $this->main_model->calculate_sale_payments($transaction_id);
        		$this->main_model->calculate_sale_total($transaction_id);
        		$arr_return = array('status' => true,'message' => 'Payment assigned to POS Order successfully.','status_code' => 1);
	        } elseif ($sale_total_paid > $sale_total) {
	        	$arr_return = array('status' => false,'message' => 'Assigning this Payment will result in an overpayment. <br/><br/>Required Amount: ' . number_format((float)$sale_total, 2) . '<br/>Payment Amount: ' . number_format((float)$sale_total_paid, 2) . '<br/><br/>Do you wish to proceed in completing this transaction and set the excess amount as change?','status_code' => 2);
	        }
		}

		return $arr_return;
	}

	function submit_paybill_overpayment_assign_transaction(){
		$transaction_type = $this->input->post('transaction_type');
		$transaction_id = $this->input->post('transaction_id');
		$paybill_payment_id = $this->input->post('paybill_payment_id');
		
		$arr_return = array('status' => false, 'message' => '', 'status_code' => 1);

		if ($transaction_type == 'Online Order') {

			$order_total = 0;

			$online_order = $this->sales_model->get_online_order($transaction_id);

			foreach ($online_order as $row) {
				$order_total = (double)$row->ord_total;
			}

			$amount_paid = 0;
			$this->db->from('paybill_payments');
	    	$this->db->where( array('bill_reference_number'=>$transaction_id, 'transaction_completed'=>0, 'paybill_payment_id !='=>$paybill_payment_id));
	    	$payments =  $this->db->get()->result();

	    	foreach($payments as $row){
	            $amount_paid = $amount_paid + $row->transaction_amount;
	        }

	        $payment_amount = 0;
	        $paybill_payment = $this->get_paybill_payment($paybill_payment_id);

	        foreach ($paybill_payment as $row) {
	        	$payment_amount = $payment_amount + $row->transaction_amount;
	        }

	        $total_amount_paid = $amount_paid + $payment_amount;

	        //$change = $total_amount_paid - $order_total;

        	//UPDATE PAYMENTS TABLE
        	$data = array(
				'bill_reference_number' => $transaction_id
			);
			$this->db->where(array('paybill_payment_id' => $paybill_payment_id));
			$this->db->update('paybill_payments', $data);

			//UPDATE ORDER SUMMARY
	        $data = array(
	            'ord_payment_method' => 'Mpesa',
	            'ord_order_status' => 1,
	            'ord_change' => $total_amount_paid - $order_total
	        );
	        $this->db->where(array('ord_order_number'=> $transaction_id));
	        $this->db->update('order_summary', $data);

	        $order = $this->sales_model->get_online_order($transaction_id);
	        foreach($order as $row){
	            $customer_id = $row->ord_customer_id;
	            $affiliate_code = $row->ord_affiliate_code;
	            $order_amount = $row->ord_total;
	            $affiliate_click_id = $row->ord_affiliate_click_id;
	        }

	        $data = array(
	            'ord_order_number' => $transaction_id,
	            'customer_id' => $customer_id,
	            'transaction_completed' => 1
	        );
	        $this->db->where(array('bill_reference_number'=>$transaction_id));
	        $this->db->update('paybill_payments', $data);

	        //AFFILIATE REFERRAL
	        if ($affiliate_code != '') {
	            $affiliate = $this->affiliates_model->get_affiliate_by_code($affiliate_code);
	            foreach ($affiliate as $row) {
	                $affiliate_id = $row->affiliate_id;
	                $affiliate_package_id = $row->affiliate_package_id;
	                $commission = $row->commission;
	                $total_commissions = $row->total_commissions;
	                $commissions_balance = $row->commissions_balance;
	            }

	            $commission_amount = (((float)$commission/100) * (float)$order_amount);
	            $commissions_balance = $commissions_balance + $commission_amount;

	            //AFFILIATE REFERRALS TABLE
	            $data = array(
	                'affiliate_id' => $affiliate_id,
	                'ord_order_number' => $transaction_id,
	                'order_amount' => $order_amount,
	                'affiliate_package_id' => $affiliate_package_id,
	                'commission' => $commission,
	                'commission_amount' => $commission_amount,
	                'commissions_balance' => $commissions_balance,
	                'affiliate_click_id' => $affiliate_click_id
	            );  

	            $this->db->insert('affiliate_referrals', $data);

	            //AFFILIATES TABLE
	            $data = array(
	                'total_commissions' => $total_commissions + $commission_amount,
	                'commissions_balance' => $commissions_balance + $commission_amount
	            ); 
	            $this->db->where( array('affiliate_code' => $affiliate_code));
	            $this->db->update('affiliates', $data);
	        }
	        $arr_return = array('status' => true,'message' => 'Payment assigned to Online Order and transaction completed successfully.');

		} elseif ($transaction_type == 'Pos Order') {

			$sale_total_paid = 0;
			$total_paid = 0;
	        $this->db->select("*");
	        $this->db->from('pos_payments');       
	        $this->db->where( array('pos_sale_id' => $transaction_id, 'is_void' => 0));
	        $pos_payments = $this->db->get()->result();

	        foreach ($pos_payments as $row) {
	            $total_paid = $total_paid + $row->payment_amount;
	        }

	        $sale_total = 0;
	        $pos_sale_number = '';
	        $this->db->select("*");
	        $this->db->from('pos_sales');       
	        $this->db->where( array('pos_sale_id' => $transaction_id));
	        $pos_sale = $this->db->get()->result();

	        foreach ($pos_sale as $row) {
	            $sale_total = $row->total_sale;
	            $pos_sale_number = $row->pos_sale_number;
	        }

	        $payment_amount = 0;
            $paybill_payment = $this->get_paybill_payment($paybill_payment_id);

            foreach ($paybill_payment as $row) {
            	$payment_amount = $payment_amount + $row->transaction_amount;
            }

	        $sale_total_paid = $total_paid + $payment_amount;

        	//UPDATE PAYMENTS TABLE
        	$data = array(
				'bill_reference_number' => $pos_sale_number
			);
			$this->db->where(array('paybill_payment_id' => $paybill_payment_id));
			$this->db->update('paybill_payments', $data);

			//ADD POS PAYMENT
        	$data = array(
                'pos_sale_id' => $transaction_id,
                'payment_amount' => $payment_amount,
                'payment_method' => 'MPESA',
                'reference_number' => '',
                'payment_note' => '',
                'paybill_payment_id' => $paybill_payment_id,
                'system_user_id' => $this->session->userdata('system_user_id')
            );
            $res = $this->db->insert('pos_payments', $data);
            $pos_payment_id = $this->db->insert_id();

            //UPDATE PAYBILL PAYMENTS TABLE
            if ($res) {
                $data = array(
                    'pos_sale_id' => $transaction_id,
                    'transaction_completed' => 1
                );
                $this->db->where( array('paybill_payment_id' => $paybill_payment_id));
                $this->db->update('paybill_payments', $data);
            }

            $this->main_model->calculate_sale_payments($transaction_id);
    		$this->main_model->calculate_sale_total($transaction_id);
    		$arr_return = array('status' => true,'message' => 'Payment assigned to POS Order successfully.');
		}

		return $arr_return;

	}


	//PESAPAL PAYMENTS
	function get_pesapal_payments(){
		// $pesapal_payment_status = $this->input->post('pesapal_payment_status');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

		$this->db->select("pp.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb");
		$this->db->from('pesapal_payments pp');
		$this->db->join('customers c', 'c.customer_id = pp.customer_id', 'left outer');

		// if ($pesapal_payment_status != ''){
  //     		$this->db->where( array('pesapal_payments.transaction_completed' => $pesapal_payment_status));
  //       }

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

		return $this->db->get()->result();
	}

}