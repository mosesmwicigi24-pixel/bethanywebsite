<?php

class Reports_model extends CI_Model {

	function get_total_sales_including_tax(){

		$total_sales_including_tax = 0;

        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
		$outlet_id = $this->input->post('outlet_id');


		//POS SALES
		$pos_sales = 0;

		$this->db->select("COALESCE(SUM(ps.total_sale),0) AS 'total_sale'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1));
        if ($outlet_id != ''){
      		$this->db->where( array('ps.outlet_id' => $outlet_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_sales = $row->total_sale;
        }

        //ONLINE SALES
        $online_sales = 0;

		$this->db->select("COALESCE(SUM(os.ord_total),0) AS 'total_sale'");
        $this->db->from('order_summary os');
        $this->db->where(array('os.ord_order_status != ' => 0));
        $this->db->where(array('os.ord_order_status != ' => 1));
        $this->db->where(array('os.ord_order_status != ' => 4));
        if ($outlet_id != ''){
      		$this->db->where( array('os.ord_dispatch_outlet_id' => $outlet_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(os.ord_dispatch_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(os.ord_dispatch_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_sales = $row->total_sale;
        }

        $total_sales_including_tax = $pos_sales + $online_sales;

        return $total_sales_including_tax;
	}

	function get_total_sales_tax() {
		
		$total_sales_tax = 0;

        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
		$outlet_id = $this->input->post('outlet_id');


		//POS SALES
		$pos_tax = 0;

		$this->db->select("COALESCE(SUM(ps.total_tax),0) AS 'total_tax'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1));
        if ($outlet_id != ''){
      		$this->db->where( array('ps.outlet_id' => $outlet_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_tax = $row->total_tax;
        }

        //ONLINE SALES
        $online_tax = 0;

		$this->db->select("COALESCE(SUM(os.ord_tax_total),0) AS 'total_tax'");
        $this->db->from('order_summary os');
        $this->db->where(array('os.ord_order_status != ' => 0));
        $this->db->where(array('os.ord_order_status != ' => 1));
        $this->db->where(array('os.ord_order_status != ' => 4));
        if ($outlet_id != ''){
      		$this->db->where( array('os.ord_dispatch_outlet_id' => $outlet_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(os.ord_dispatch_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(os.ord_dispatch_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_tax = $row->total_tax;
        }

        $total_sales_tax = $pos_tax - $online_tax;

        return $total_sales_tax;

	}

	function get_total_sales_excluding_tax(){
		
		$total_sales_excluding_tax = 0;

        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
		$outlet_id = $this->input->post('outlet_id');


		//POS SALES
		$pos_sales = 0;
		$pos_tax = 0;
		$pos_sales_minus_tax = 0;

		$this->db->select("COALESCE(SUM(ps.total_sale),0) AS 'total_sale', COALESCE(SUM(ps.total_tax),0) AS 'total_tax'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1));
        if ($outlet_id != ''){
      		$this->db->where( array('ps.outlet_id' => $outlet_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_sales = $row->total_sale;
            $pos_tax = $row->total_tax;
        }

        $pos_sales_minus_tax = $pos_sales - $pos_tax;

        //ONLINE SALES
        $online_sales = 0;
        $online_tax = 0;
        $online_sales_minus_tax = 0;

		$this->db->select("COALESCE(SUM(os.ord_total),0) AS 'total_sale', COALESCE(SUM(os.ord_tax_total),0) AS 'total_tax'");
        $this->db->from('order_summary os');
        $this->db->where(array('os.ord_order_status != ' => 0));
        $this->db->where(array('os.ord_order_status != ' => 1));
        $this->db->where(array('os.ord_order_status != ' => 4));
        if ($outlet_id != ''){
      		$this->db->where( array('os.ord_dispatch_outlet_id' => $outlet_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(os.ord_dispatch_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(os.ord_dispatch_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_sales = $row->total_sale;
            $online_tax = $row->total_tax;
        }

        $online_sales_minus_tax = $online_sales - $online_tax;

        $total_sales_excluding_tax = $pos_sales_minus_tax + $online_sales_minus_tax;

        return $total_sales_excluding_tax;
	}

	function get_cost_of_goods_sold(){

		$cost_of_goods_sold = 0;

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $outlet_id = $this->input->post('outlet_id');

        $this->db->select('p.product_name,
		    p.product_id,
		    op.product_variation_id,
		  	IFNull(QtyBeginningStockIn.Qty_In, 0) QtyBeginningStockIn,
		  	IFNull(BeginningStockIn.Amount, 0) BeginningStockIn,

		  	IFNull(QtyBeginningPurchases.Qty_In, 0) QtyBeginningPurchases,
		  	IFNull(BeginningPurchases.Amount, 0) BeginningPurchases,

		  	IFNull(QtyBeginningStockOut.Qty_Out, 0) QtyBeginningStockOut,

		  	IFNull(QtyEndingStockIn.Qty_In, 0) QtyEndingStockIn,		  	
		  	IFNull(EndingStockIn.Amount, 0) EndingStockIn,

		  	IFNull(QtyEndingPurchases.Qty_In, 0) QtyEndingPurchases,
		  	IFNull(EndingPurchases.Amount, 0) EndingPurchases,

		  	IFNull(QtyEndingStockOut.Qty_Out, 0) QtyEndingStockOut,
		  	IFNull(PeriodStockIn.Amount, 0) StockIn');
		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');

		$this->db->join('(SELECT SUM(quantity) as Qty_In, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND DATE_FORMAT(created_on, "%Y-%m-%d") < STR_TO_DATE("' . $date_from . '", "%Y-%m-%d") group by product_variation_id, product_id) QtyBeginningStockIn', 'QtyBeginningStockIn.product_id = p.product_id AND QtyBeginningStockIn.product_variation_id = op.product_variation_id', 'LEFT');
		$this->db->join('(SELECT SUM(quantity * unit_price) as Amount, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND DATE_FORMAT(created_on, "%Y-%m-%d") < STR_TO_DATE("' . $date_from . '", "%Y-%m-%d") group by product_variation_id, product_id) BeginningStockIn', 'BeginningStockIn.product_id = p.product_id AND BeginningStockIn.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->join('(SELECT SUM(quantity) as Qty_In, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Opening Stock" OR transaction_description = "Goods Received") AND DATE_FORMAT(created_on, "%Y-%m-%d") < STR_TO_DATE("' . $date_from . '", "%Y-%m-%d") group by product_variation_id, product_id ORDER BY created_on DESC LIMIT 2) QtyBeginningPurchases', 'QtyBeginningPurchases.product_id = p.product_id AND QtyBeginningPurchases.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->join('(SELECT SUM(quantity * unit_price) as Amount, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Opening Stock" OR transaction_description = "Goods Received") AND DATE_FORMAT(created_on, "%Y-%m-%d") < STR_TO_DATE("' . $date_from . '", "%Y-%m-%d") group by product_variation_id, product_id ORDER BY created_on DESC LIMIT 2) BeginningPurchases', 'BeginningPurchases.product_id = p.product_id AND BeginningPurchases.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->join('(SELECT SUM(quantity) as Qty_Out, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "OUT" AND DATE_FORMAT(created_on, "%Y-%m-%d") < STR_TO_DATE("' . $date_from . '", "%Y-%m-%d") group by product_variation_id, product_id) QtyBeginningStockOut', 'QtyBeginningStockOut.product_id = p.product_id AND QtyBeginningStockOut.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->join('(SELECT SUM(quantity) as Qty_In, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND DATE_FORMAT(created_on, "%Y-%m-%d") <= STR_TO_DATE("' . $date_to . '", "%Y-%m-%d") group by product_variation_id, product_id) QtyEndingStockIn', 'QtyEndingStockIn.product_id = p.product_id AND QtyEndingStockIn.product_variation_id = op.product_variation_id', 'LEFT');		

		$this->db->join('(SELECT SUM(quantity * unit_price) as Amount, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND DATE_FORMAT(created_on, "%Y-%m-%d") <= STR_TO_DATE("' . $date_to . '", "%Y-%m-%d") group by product_variation_id, product_id) EndingStockIn', 'EndingStockIn.product_id = p.product_id AND EndingStockIn.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->join('(SELECT SUM(quantity) as Qty_In, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Opening Stock" OR transaction_description = "Goods Received") AND DATE_FORMAT(created_on, "%Y-%m-%d") <= STR_TO_DATE("' . $date_to . '", "%Y-%m-%d") group by product_variation_id, product_id ORDER BY created_on DESC LIMIT 2) QtyEndingPurchases', 'QtyEndingPurchases.product_id = p.product_id AND QtyEndingPurchases.product_variation_id = op.product_variation_id', 'LEFT');		

		$this->db->join('(SELECT SUM(quantity * unit_price) as Amount, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Opening Stock" OR transaction_description = "Goods Received") AND DATE_FORMAT(created_on, "%Y-%m-%d") <= STR_TO_DATE("' . $date_to . '", "%Y-%m-%d") group by product_variation_id, product_id ORDER BY created_on DESC LIMIT 2) EndingPurchases', 'EndingPurchases.product_id = p.product_id AND EndingPurchases.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->join('(SELECT SUM(quantity) as Qty_Out, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "OUT" AND DATE_FORMAT(created_on, "%Y-%m-%d") <= STR_TO_DATE("' . $date_to . '", "%Y-%m-%d") group by product_variation_id, product_id) QtyEndingStockOut', 'QtyEndingStockOut.product_id = p.product_id AND QtyEndingStockOut.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->join('(SELECT SUM(quantity * unit_price) as Amount, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Goods Received") AND DATE_FORMAT(created_on, "%Y-%m-%d") >= STR_TO_DATE("' . $date_from . '", "%Y-%m-%d") AND DATE_FORMAT(created_on, "%Y-%m-%d") <= STR_TO_DATE("' . $date_to . '", "%Y-%m-%d") group by product_variation_id, product_id) PeriodStockIn', 'PeriodStockIn.product_id = p.product_id AND PeriodStockIn.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->where( array('p.is_deleted' => 0));

		if ($outlet_id != ''){
      		$this->db->where( array('op.outlet_id' => $outlet_id));
        }

		$this->db->group_by('op.product_variation_id, p.product_id');

		$results =  $this->db->get()->result();

		$TotalBeginningStock = 0;
		$TotalEndingStock = 0;
		$TotalStockIn = 0;

		foreach ($results  as $row) {
			if ($row->QtyBeginningPurchases > 0){
				$beginning_avg_buying_price = ($row->BeginningPurchases / $row->QtyBeginningPurchases);
			} else {
				$beginning_avg_buying_price = 0;
			}
			$TotalBeginningStock = $TotalBeginningStock + ($beginning_avg_buying_price * ($row->QtyBeginningStockIn - $row->QtyBeginningStockOut));

			if ($row->QtyEndingPurchases > 0){
				$ending_avg_buying_price = ($row->EndingPurchases / $row->QtyEndingPurchases);
			} else {
				$ending_avg_buying_price = 0;
			}
			$TotalEndingStock = $TotalEndingStock + ($ending_avg_buying_price * ($row->QtyEndingStockIn - $row->QtyEndingStockOut));

			$TotalStockIn = $TotalStockIn + $row->StockIn;
		}

		$cost_of_goods_sold = ($TotalBeginningStock + $TotalStockIn) - $TotalEndingStock;

		return $cost_of_goods_sold;
	}

	function get_pos_sales_summary_chart_data(){
		$date_from = $this->input->post('date_from');
    	$date_to = $this->input->post('date_to');
		$outlet_id = $this->input->post('outlet_id');

		$diff = abs(strtotime($date_to) - strtotime($date_from));

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		if ($years < 1 && $months < 2 ) {

			$this->db->select('DATE_FORMAT(created_on, "%Y-%m-%d") AS "sale_date", COALESCE(SUM(total_sale),0) AS "total_sale"');
			$this->db->from('pos_sales');
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
			$this->db->where(array('is_void' => 0, 'is_held' => 0, 'is_completed' => 1));
			if ($outlet_id != ''){
	      		$this->db->where( array('outlet_id' => $outlet_id));
	        }
			$this->db->group_by('DATE_FORMAT(created_on, "%Y-%m-%d")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Day", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "POS Sales", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('M d Y', strtotime($res->sale_date)),"f" => null), 
	                array("v" => number_format($res->total_sale,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		} elseif ($years < 1 && $months >= 2 ) {

			$this->db->select('DATE_FORMAT(created_on, "%Y-%m") AS "sale_month", COALESCE(SUM(total_sale),0) AS "total_sale"');
			$this->db->from('pos_sales');
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
			$this->db->where(array('is_void' => 0, 'is_held' => 0, 'is_completed' => 1));
			if ($outlet_id != ''){
	      		$this->db->where( array('outlet_id' => $outlet_id));
	        }
			$this->db->group_by('DATE_FORMAT(created_on, "%Y-%m")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Month", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "POS Sales", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('M Y', strtotime($res->sale_month)),"f" => null), 
	                array("v" => number_format($res->total_sale,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		} elseif ($years >= 1) {

			$this->db->select('DATE_FORMAT(created_on, "%Y") AS "sale_year", COALESCE(SUM(total_sale),0) AS "total_sale"');
			$this->db->from('pos_sales');
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
			$this->db->where(array('is_void' => 0, 'is_held' => 0, 'is_completed' => 1));
			if ($outlet_id != ''){
	      		$this->db->where( array('outlet_id' => $outlet_id));
	        }
			$this->db->group_by('DATE_FORMAT(created_on, "%Y")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Year", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "POS Sales", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('Y', strtotime($res->sale_year)),"f" => null), 
	                array("v" => number_format($res->total_sale,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		}

	}

	function get_online_sales_summary_chart_data(){
		$date_from = $this->input->post('date_from');
    	$date_to = $this->input->post('date_to');
		$outlet_id = $this->input->post('outlet_id');

		$diff = abs(strtotime($date_to) - strtotime($date_from));

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		if ($years < 1 && $months < 2 ) {

			$this->db->select('DATE_FORMAT(ord_dispatch_date, "%Y-%m-%d") AS "sale_date", COALESCE(SUM(ord_total),0) AS "total_sale"');
			$this->db->from('order_summary');
			$this->db->where('DATE_FORMAT(ord_dispatch_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(ord_dispatch_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
			$this->db->where(array('ord_order_status != ' => 0));
	        $this->db->where(array('ord_order_status != ' => 1));
	        $this->db->where(array('ord_order_status != ' => 4));
			if ($outlet_id != ''){
	      		$this->db->where( array('ord_dispatch_outlet_id' => $outlet_id));
	        }
			$this->db->group_by('DATE_FORMAT(ord_dispatch_date, "%Y-%m-%d")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Day", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "Online Sales", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('M d Y', strtotime($res->sale_date)),"f" => null), 
	                array("v" => number_format($res->total_sale,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		} elseif ($years < 1 && $months >= 2 ) {

			$this->db->select('DATE_FORMAT(ord_dispatch_date, "%Y-%m") AS "sale_month", COALESCE(SUM(ord_total),0) AS "total_sale"');
			$this->db->from('order_summary');
			$this->db->where('DATE_FORMAT(ord_dispatch_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(ord_dispatch_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
			$this->db->where(array('ord_order_status != ' => 0));
	        $this->db->where(array('ord_order_status != ' => 1));
	        $this->db->where(array('ord_order_status != ' => 4));
			if ($outlet_id != ''){
	      		$this->db->where( array('ord_dispatch_outlet_id' => $outlet_id));
	        }
			$this->db->group_by('DATE_FORMAT(ord_dispatch_date, "%Y-%m")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Month", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "Online Sales", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('M Y', strtotime($res->sale_month)),"f" => null), 
	                array("v" => number_format($res->total_sale,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		} elseif ($years >= 1) {

			$this->db->select('DATE_FORMAT(ord_dispatch_date, "%Y") AS "sale_year", COALESCE(SUM(ord_total),0) AS "total_sale"');
			$this->db->from('order_summary');
			$this->db->where('DATE_FORMAT(ord_dispatch_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(ord_dispatch_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
			$this->db->where(array('ord_order_status != ' => 0));
	        $this->db->where(array('ord_order_status != ' => 1));
	        $this->db->where(array('ord_order_status != ' => 4));
			if ($outlet_id != ''){
	      		$this->db->where( array('ord_dispatch_outlet_id' => $outlet_id));
	        }
			$this->db->group_by('DATE_FORMAT(ord_dispatch_date, "%Y")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Year", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "Online Sales", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('Y', strtotime($res->sale_year)),"f" => null), 
	                array("v" => number_format($res->total_sale,2,".",""),"f" => null) 
	            ); 
	        }
	        return $response;
		}
	}

	function get_sales_by_items(){

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $outlet_id = $this->input->post('outlet_id');

        $this->db->select('p.product_name,
		    p.product_id,
		    op.product_variation_id,
		    IFNull(PosSales.total_sub_total,0) posTotalSubTotal,
		    IFNull(PosSales.total_line_total,0) posTotalLineTotal,
		    IFNull(PosSales.total_tax_amount,0) posTotalTaxAmount,
		    IFNull(PosSales.total_discount_amount,0) posTotalDiscountAmount,
		    IFNull(PosSales.total_quantity,0) posTotalQuantity,
		    IFNull(OnlineSales.total_sub_total,0) onlineTotalSubTotal,
		    IFNull(OnlineSales.total_line_total,0) onlineTotalLineTotal,
		    IFNull(OnlineSales.total_tax_amount,0) onlineTotalTaxAmount,
		    IFNull(OnlineSales.total_discount_amount,0) onlineTotalDiscountAmount,
		    IFNull(OnlineSales.total_quantity,0) onlineTotalQuantity,'
		);
		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');

		$this->db->join('(SELECT COALESCE(SUM(pos_sale_details.sub_total),0) as total_sub_total, COALESCE(SUM(pos_sale_details.line_total),0) as total_line_total, COALESCE(SUM(pos_sale_details.tax_amount),0) as total_tax_amount, COALESCE(SUM(pos_sale_details.discount_amount),0) as total_discount_amount, COALESCE(SUM(pos_sale_details.quantity),0) as total_quantity ,product_id, product_variation_id FROM pos_sale_details JOIN pos_sales ON pos_sales.pos_sale_id = pos_sale_details.pos_sale_id WHERE pos_sales.is_void = 0 AND pos_sales.is_held = 0 AND pos_sales.is_completed = 1 AND DATE_FORMAT(pos_sales.created_on, "%Y-%m-%d") >= STR_TO_DATE("' . $date_from . '", "%Y-%m-%d") AND DATE_FORMAT(pos_sales.created_on, "%Y-%m-%d") <= STR_TO_DATE("' . $date_to . '", "%Y-%m-%d") group by product_variation_id, product_id) PosSales', 'PosSales.product_id = p.product_id AND PosSales.product_variation_id = op.product_variation_id', 'LEFT OUTER');

		$this->db->join('(SELECT COALESCE(SUM(order_details.ord_det_discount_price_total),0) as total_sub_total, COALESCE(SUM(order_details.ord_det_price_total),0) as total_line_total, COALESCE(SUM(order_details.ord_det_tax_total),0) as total_tax_amount, COALESCE(SUM((order_details.ord_det_price * order_details.ord_det_quantity) - (order_details.ord_det_discount_price * order_details.ord_det_quantity)),0) as total_discount_amount, COALESCE(SUM(order_details.ord_det_quantity),0) as total_quantity, ord_det_product_id, ord_det_product_variation_id FROM order_details JOIN order_summary ON order_summary.ord_order_number = order_details.ord_det_order_number_fk WHERE order_summary.ord_order_status != 0 AND order_summary.ord_order_status != 1 AND order_summary.ord_order_status != 4 AND DATE_FORMAT(order_summary.ord_dispatch_date, "%Y-%m-%d") >= STR_TO_DATE("' . $date_from . '", "%Y-%m-%d") AND DATE_FORMAT(order_summary.ord_dispatch_date, "%Y-%m-%d") <= STR_TO_DATE("' . $date_to . '", "%Y-%m-%d") group by ord_det_product_variation_id, ord_det_product_id) OnlineSales', 'OnlineSales.ord_det_product_id = p.product_id AND OnlineSales.ord_det_product_variation_id = op.product_variation_id', 'LEFT OUTER');

		$this->db->where( array('p.is_deleted' => 0));
		
		if ($outlet_id != ''){
      		$this->db->where( array('op.outlet_id' => $outlet_id));
      	}

		$this->db->group_by('op.product_variation_id, p.product_id');

		$sales_by_items = $this->db->get()->result();

        $i = 0;
        foreach($sales_by_items as $row){
            $sales_by_items[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $sales_by_items;

		// return  $this->db->get()->result();
	}

	function get_product_variation_attributes($product_variation_id) {
    	$this->db->select('pva.*, pa.product_attribute_name, pav.product_attribute_value');
		$this->db->from('product_variation_attributes pva');
		$this->db->join('product_attributes pa', 'pa.product_attribute_id = pva.product_attribute_id', 'left outer');
		$this->db->join('product_attribute_values pav', 'pav.product_attribute_value_id = pva.product_attribute_value_id', 'left outer');

		$this->db->where( array('pva.product_variation_id' => $product_variation_id, 'pva.is_deleted'=>0));
		return $this->db->get()->result();
    }

    function get_pos_sales_transactions(){

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $outlet_id = $this->input->post('outlet_id');
        $sale_type = $this->input->post('sale_type');
        $sale_status = $this->input->post('sale_status');
        $system_user_id = $this->input->post('system_user_id');


        $this->db->select("ps.*, o.outlet_name, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_sales ps');     
        $this->db->join('outlets o', 'o.outlet_id = ps.outlet_id', 'left outer');
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer');  

        $this->db->where( array('ps.is_held' => 0, 'ps.is_completed' => 1));

        if ($outlet_id != ''){
      		$this->db->where( array('ps.outlet_id' => $outlet_id));
        }
        if ($sale_type != ''){
      		$this->db->where( array('ps.sale_type' => $sale_type));
        }
        if ($system_user_id != ''){
      		$this->db->where( array('ps.system_user_id' => $system_user_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        if ($sale_status == 'Valid') {
        	$this->db->where( array('ps.is_void' => 0));
        } elseif ($sale_status == 'Paid') {
        	$this->db->where( array('ps.is_void' => 0));
        	$this->db->where('ps.total_paid >= ps.total_sale');
        } elseif ($sale_status == 'Partially Paid') {
        	$this->db->where( array('ps.is_void' => 0));
        	$this->db->where( array('ps.total_paid >' => 0));
        	$this->db->where('ps.total_paid < ps.total_sale');
        } elseif ($sale_status == 'Unpaid') {
        	$this->db->where( array('ps.is_void' => 0));
        	$this->db->where( array('ps.total_paid' => 0));
        } elseif ($sale_status == 'Void') {
        	$this->db->where( array('ps.is_void' => 1));
        }

        $this->db->order_by("ps.pos_sale_id", "desc");
        
        return $this->db->get()->result();
    }

    function get_item_sales(){

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $outlet_id = $this->input->post('outlet_id');
        $product_id = $this->input->post('product_id');
        $system_user_id = $this->input->post('system_user_id');
        $customer_id = $this->input->post('customer_id');


        $this->db->select("psd.*, ps.pos_sale_number, ps.sale_type, ps.sale_date, ps.system_user_id, ps.customer_id, ps.customer_name, ps.outlet_id, ps.created_on, p.product_name, p.product_sku_code, u.unit_code, u.unit_name, b.brand_reference_id, b.brand_name, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value, o.outlet_name, c.first_name, c.last_name, c.email_address, c.phone_number, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_sale_details psd');  
        $this->db->join('pos_sales ps', 'ps.pos_sale_id = psd.pos_sale_id'); 
        $this->db->join('products p', 'p.product_id = psd.product_id');  

        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = psd.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psd.tax_rate_id', 'left outer');

        $this->db->join('outlets o', 'o.outlet_id = ps.outlet_id', 'left outer');
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer');  

        $this->db->where( array('ps.is_held' => 0, 'ps.is_completed' => 1));

        if ($outlet_id != ''){
      		$this->db->where( array('ps.outlet_id' => $outlet_id));
        }
        if ($product_id != ''){
      		$this->db->where( array('psd.product_id' => $product_id));
        }
        if ($system_user_id != ''){
      		$this->db->where( array('ps.system_user_id' => $system_user_id));
        }
        if ($customer_id != ''){
      		$this->db->where( array('ps.customer_id' => $customer_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        $this->db->order_by("ps.pos_sale_id", "desc");

        $pos_sale_details = $this->db->get()->result();

        $i = 0;
        foreach($pos_sale_details as $row){
            $pos_sale_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $pos_sale_details;
        
        // return $this->db->get()->result();
    }

    function get_credit_list_report() {

        $customer_id = $this->input->post('customer_id');
        $system_user_id = $this->input->post('system_user_id');
        $chk_cash_sales = $this->input->post('chk_cash_sales');

        $this->db->select("ps.*, o.outlet_name, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, ct.credit_term, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_sales ps');     
        $this->db->join('outlets o', 'o.outlet_id = ps.outlet_id', 'left outer');
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');
        $this->db->join('credit_terms ct', 'ct.credit_term_id = ps.credit_term_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer');  

        $this->db->where( array('ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.is_void' => 0));
        $this->db->where('ps.total_paid < ps.total_sale');
        
		if($chk_cash_sales == 'on'){
			// $chk_cash_sales = 1;
		}else{
			// $chk_cash_sales = 0;
			$this->db->where( array('ps.sale_type' => 'CREDIT SALE'));
		}        

        if ($customer_id != ''){
      		$this->db->where( array('ps.customer_id' => $customer_id));
        }
        if ($system_user_id != ''){
      		$this->db->where( array('ps.system_user_id' => $system_user_id));
        }

        $this->db->order_by("ps.pos_sale_id", "desc");
        
        return $this->db->get()->result();
    }

    function get_customer_aging_report() {

    	$car_date = $this->input->post('car_date');
    	if ($car_date != '') {
    		$car_date = date('Y-m-d', strtotime($car_date));
    	} else {
    		$car_date = date('Y-m-d');
    	}

    	$this->db->select("c.*, ps.pos_sale_id, 
    		SUM(IF(DATEDIFF(STR_TO_DATE('" . $car_date ."', '%Y-%m-%d'), DATE_FORMAT(ps.sale_date, '%Y-%m-%d')) BETWEEN 0 AND 30, (ps.total_sale) - (ps.total_paid), 0)) AS 'age_0_30', 
    		SUM(IF(DATEDIFF(STR_TO_DATE('" . $car_date ."', '%Y-%m-%d'), DATE_FORMAT(ps.sale_date, '%Y-%m-%d') ) BETWEEN 31 AND 60, (ps.total_sale) - (ps.total_paid), 0)) AS 'age_31_60', 
    		SUM(IF(DATEDIFF(STR_TO_DATE('" . $car_date ."', '%Y-%m-%d'), DATE_FORMAT(ps.sale_date, '%Y-%m-%d') ) BETWEEN 61 AND 90, (ps.total_sale) - (ps.total_paid), 0)) AS 'age_61_90', 
    		SUM(IF(DATEDIFF(STR_TO_DATE('" . $car_date ."', '%Y-%m-%d'), DATE_FORMAT(ps.sale_date, '%Y-%m-%d') ) > 90, (ps.total_sale) - (ps.total_paid), 0)) AS 'age_gt_90', 
    		SUM(ps.total_sale - ps.total_paid) AS 'total_balance'");

    	$this->db->from('customers c');
    	$this->db->join('pos_sales ps', 'ps.customer_id = c.customer_id', 'left outer');
    	$this->db->where(array('ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.is_void' => 0));
    	$this->db->where('ps.total_sale > ps.total_paid');

    	$this->db->group_by('c.customer_id');
    	$this->db->order_by("total_balance", "desc");

    	return $this->db->get()->result();
    	//  FROM customers c JOIN pos_sales ps ON c.customer_id = ps.customer_id WHERE ps.total_sale > ps.total_paid AND ps.is_void = 0 AND ps.is_held = 0 AND ps.is_completed = 1 GROUP BY c.customer_id ORDER BY totalBalance DESC
    }

    function get_online_sales_transactions() {

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $outlet_id = $this->input->post('outlet_id');

		$this->db->select("os.*, customers.customer_id, customers.first_name, customers.last_name, customers.phone_number, customers.email_address, pesapal_payments.pesapal_payment_id");
		$this->db->from('order_summary os');
		$this->db->join('customers', 'customers.customer_id = os.ord_customer_id', 'left outer');
		$this->db->join('pesapal_payments', 'pesapal_payments.merchant_reference_id = os.ord_merchant_reference_id', 'left outer');

        $this->db->where(array('os.ord_order_status != ' => 0));
        $this->db->where(array('os.ord_order_status != ' => 1));
        $this->db->where(array('os.ord_order_status != ' => 4));

        if ($outlet_id != ''){
      		$this->db->where( array('os.ord_dispatch_outlet_id' => $outlet_id));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(os.ord_dispatch_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(os.ord_dispatch_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

		return $this->db->get()->result();
    }

    function get_stock_report(){
		$this->db->select('outlets.*');
		$this->db->from('outlets');
		$this->db->where( array('is_deleted' => 0));
		$this->db->order_by("sort_key", "desc");

		$outlets = $this->db->get()->result();

        $i = 0;
        foreach($outlets as $row){
            $outlets[$i]->inventory = $this->get_outlet_inventory($row->outlet_id);
            $i++;
        }
        return $outlets;
    }

    function get_outlet_stock_report($outlet_id){
		$this->db->select('outlets.*');
		$this->db->from('outlets');
		$this->db->where( array('outlet_id' => $outlet_id));

		$outlets = $this->db->get()->result();

        $i = 0;
        foreach($outlets as $row){
            $outlets[$i]->inventory = $this->get_outlet_inventory($row->outlet_id);
            $i++;
        }
        return $outlets;
    }    

    function get_outlet_inventory($outlet_id){

    	$this->db->select('p.product_name,
		    p.product_id,
		    p.product_sku_code,
		    op.product_variation_id,
		    op.opening_stock,
		    op.available_stock,
		    op.reorder_level,
		    op.regular_price,
		    op.sale_price,
		  	IFNull(QtyStockIn.Qty_In, 0) QtyStockIn,
		  	IFNull(StockIn.Amount, 0) StockIn');

		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');

		$this->db->join('(SELECT SUM(quantity) as Qty_In, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Opening Stock" OR transaction_description = "Goods Received") group by product_variation_id, product_id ORDER BY created_on DESC LIMIT 2) QtyStockIn', 'QtyStockIn.product_id = p.product_id AND QtyStockIn.product_variation_id = op.product_variation_id', 'LEFT');
		$this->db->join('(SELECT SUM(quantity * unit_price) as Amount, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Opening Stock" OR transaction_description = "Goods Received") group by product_variation_id, product_id ORDER BY created_on DESC LIMIT 2) StockIn', 'StockIn.product_id = p.product_id AND StockIn.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->where( array('p.is_deleted' => 0));
		$this->db->where( array('op.outlet_id' => $outlet_id));

		$this->db->group_by('op.product_variation_id, p.product_id');

		$outlet_inventory = $this->db->get()->result();

        $i = 0;
        foreach($outlet_inventory as $row){
            $outlet_inventory[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $outlet_inventory;
    }

    function get_low_stocks_report(){
		$this->db->select('outlets.*');
		$this->db->from('outlets');
		$this->db->where( array('is_deleted' => 0));
		$this->db->order_by("sort_key", "desc");

		$outlets = $this->db->get()->result();

        $i = 0;
        foreach($outlets as $row){
            $outlets[$i]->inventory = $this->get_outlet_low_stocks($row->outlet_id);
            $i++;
        }
        return $outlets;
    }

    function get_outlet_low_stocks_report($outlet_id){
		$this->db->select('outlets.*');
		$this->db->from('outlets');
		$this->db->where( array('outlet_id' => $outlet_id));
		$this->db->order_by("sort_key", "desc");

		$outlets = $this->db->get()->result();

        $i = 0;
        foreach($outlets as $row){
            $outlets[$i]->inventory = $this->get_outlet_low_stocks($row->outlet_id);
            $i++;
        }
        return $outlets;
    }

    function get_outlet_low_stocks($outlet_id){

    	$this->db->select('p.product_name, p.product_id, p.product_sku_code, op.product_variation_id, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price, op.sale_price');
		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');
		$this->db->where( array('p.is_deleted' => 0));
		$this->db->where('op.available_stock <= op.reorder_level');
		$this->db->where( array('op.outlet_id' => $outlet_id));

		$this->db->group_by('op.product_variation_id, p.product_id');

		$outlet_inventory = $this->db->get()->result();

        $i = 0;
        foreach($outlet_inventory as $row){
            $outlet_inventory[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $outlet_inventory;
    }

    function get_total_payments(){

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

		$total_payments = 0;

		//POS PAYMENTS
		$pos_payments = 0;

		$this->db->select("COALESCE(SUM(pp.payment_amount),0) AS 'total_payments'");
        $this->db->from('pos_payments pp');
        $this->db->where(array('pp.is_void' => 0));
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_payments = $row->total_payments;
        }

        //ONLINE MPESA PAYMENTS
        $online_mpesa_payments = 0;

		$this->db->select("COALESCE(SUM(pp.transaction_amount),0) AS 'total_payments'");
        $this->db->from('paybill_payments pp');
        $this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_mpesa_payments = $row->total_payments;
        }

        //ONLINE PESAPAL PAYMENTS
        $online_pesapal_payments = 0;

		$this->db->select("COALESCE(SUM(pp.transaction_amount),0) AS 'total_payments'");
        $this->db->from('pesapal_payments pp');
        $this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_pesapal_payments = $row->total_payments;
        }

        $total_payments = $pos_payments + $online_mpesa_payments + $online_pesapal_payments;

        return $total_payments;    	
    }

    function get_total_pos_payments() {

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

    	$pos_payments = 0;

		$this->db->select("COALESCE(SUM(pp.payment_amount),0) AS 'total_payments'");
        $this->db->from('pos_payments pp');
        $this->db->where(array('pp.is_void' => 0));
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_payments = $row->total_payments;
        }

        return $pos_payments;
    }

    function get_total_online_payments() {

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

    	$total_online_payments = 0;

    	//ONLINE MPESA PAYMENTS
        $online_mpesa_payments = 0;

		$this->db->select("COALESCE(SUM(pp.transaction_amount),0) AS 'total_payments'");
        $this->db->from('paybill_payments pp');
        $this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_mpesa_payments = $row->total_payments;
        }

        //ONLINE PESAPAL PAYMENTS
        $online_pesapal_payments = 0;

		$this->db->select("COALESCE(SUM(pp.transaction_amount),0) AS 'total_payments'");
        $this->db->from('pesapal_payments pp');
        $this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_pesapal_payments = $row->total_payments;
        }

        $total_online_payments = $online_mpesa_payments + $online_pesapal_payments;

        return $total_online_payments;
    }

    function get_total_payment_transactions(){
    	$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

		$payment_transactions = 0;

		//POS PAYMENTS
		$pos_payment_transactions = 0;

		$this->db->select("COALESCE(COUNT(pp.pos_payment_id),0) AS 'total_transactions'");
        $this->db->from('pos_payments pp');
        $this->db->where(array('pp.is_void' => 0));
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_payment_transactions = $row->total_transactions;
        }

        //ONLINE MPESA PAYMENTS
        $online_mpesa_payment_transactions = 0;

		$this->db->select("COALESCE(COUNT(pp.paybill_payment_id),0) AS 'total_transactions'");
        $this->db->from('paybill_payments pp');
        $this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_mpesa_payment_transactions = $row->total_transactions;
        }

        //ONLINE PESAPAL PAYMENTS
        $online_pesapal_payment_transactions = 0;

		$this->db->select("COALESCE(COUNT(pp.pesapal_payment_id),0) AS 'total_transactions'");
        $this->db->from('pesapal_payments pp');
        $this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_pesapal_payment_transactions = $row->total_transactions;
        }

        $payment_transactions = $pos_payment_transactions + $online_mpesa_payment_transactions + $online_pesapal_payment_transactions;

        return $payment_transactions;
    }

    function get_total_payments_balance(){

    	$payments_balance = 0;

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        //POS SALES
		$pos_sales = 0;

		$this->db->select("COALESCE(SUM(ps.total_sale),0) AS 'total_sale'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1));
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_sales = $row->total_sale;
        }

		$total_payments = 0;

		//POS PAYMENTS
		$pos_payments = 0;

		$this->db->select("COALESCE(SUM(pp.payment_amount),0) AS 'total_payments'");
        $this->db->from('pos_payments pp');
        $this->db->where(array('pp.is_void' => 0));
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_payments = $row->total_payments;
        }

        $payments_balance = $pos_sales - $pos_payments;

        return $payments_balance;
    }

    function get_pos_total_payments_by_type($payment_type, $date_from, $date_to) {
    	$pos_payments = 0;

		$this->db->select("COALESCE(SUM(pp.payment_amount),0) AS 'total_payments'");
        $this->db->from('pos_payments pp');
        $this->db->where(array('pp.is_void' => 0, 'pp.payment_method' => $payment_type));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $pos_payments = $row->total_payments;
        }

        return $pos_payments;
    }

    function get_pos_payments_donut_data(){

    	$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

    	$response->cols[] = array( 
            "id" => "", 
            "label" => "Payment Method", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $response->cols[] = array( 
            "id" => "", 
            "label" => "Amount", 
            "pattern" => "", 
            "type" => "number" 
        );

        $cash_payments = $this->get_pos_total_payments_by_type('Cash', $date_from, $date_to);
        $response->rows[]["c"] = array( 
            array("v" => "Cash","f" => null), 
            array("v" => number_format($cash_payments,2,".",""),"f" => null) 
        );

        $mpesa_payments = $this->get_pos_total_payments_by_type('MPESA', $date_from, $date_to);
        $response->rows[]["c"] = array( 
            array("v" => "MPESA","f" => null), 
            array("v" => number_format($mpesa_payments,2,".",""),"f" => null) 
        );

        $cheque_payments = $this->get_pos_total_payments_by_type('Cheque', $date_from, $date_to);
        $response->rows[]["c"] = array( 
            array("v" => "Cheque","f" => null), 
            array("v" => number_format($cheque_payments,2,".",""),"f" => null) 
        );

        $cashapp_payments = $this->get_pos_total_payments_by_type('CashApp', $date_from, $date_to);
        $response->rows[]["c"] = array( 
            array("v" => "CashApp","f" => null), 
            array("v" => number_format($cashapp_payments,2,".",""),"f" => null) 
        );

        $wave_payments = $this->get_pos_total_payments_by_type('Wave', $date_from, $date_to);
        $response->rows[]["c"] = array( 
            array("v" => "Wave","f" => null), 
            array("v" => number_format($wave_payments,2,".",""),"f" => null) 
        ); 

        return $response;
    }

    function get_online_payments_donut_data(){

    	$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        //ONLINE MPESA PAYMENTS
        $online_mpesa_payments = 0;

		$this->db->select("COALESCE(SUM(pp.transaction_amount),0) AS 'total_payments'");
        $this->db->from('paybill_payments pp');
        $this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_mpesa_payments = $row->total_payments;
        }

        //ONLINE PESAPAL PAYMENTS
        $online_pesapal_payments = 0;

		$this->db->select("COALESCE(SUM(pp.transaction_amount),0) AS 'total_payments'");
        $this->db->from('pesapal_payments pp');
        $this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $online_pesapal_payments = $row->total_payments;
        }

    	$response->cols[] = array( 
            "id" => "", 
            "label" => "Payment Method", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $response->cols[] = array( 
            "id" => "", 
            "label" => "Amount", 
            "pattern" => "", 
            "type" => "number" 
        );

        $response->rows[]["c"] = array( 
            array("v" => "MPESA","f" => null), 
            array("v" => number_format($online_mpesa_payments,2,".",""),"f" => null) 
        );

        $response->rows[]["c"] = array( 
            array("v" => "Pesapal","f" => null), 
            array("v" => number_format($online_pesapal_payments,2,".",""),"f" => null) 
        );

        return $response;
    }

    function get_pos_payments_chart_data(){
		$date_from = $this->input->post('date_from');
    	$date_to = $this->input->post('date_to');

		$diff = abs(strtotime($date_to) - strtotime($date_from));

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		if ($years < 1 && $months < 2 ) {

			$this->db->select('DATE_FORMAT(created_on, "%Y-%m-%d") AS "payment_date", COALESCE(SUM(payment_amount),0) AS "total_payments"');
			$this->db->from('pos_payments');
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
	        $this->db->where(array('is_void' => 0));
			$this->db->group_by('DATE_FORMAT(created_on, "%Y-%m-%d")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Day", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "POS Payments", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('M d Y', strtotime($res->payment_date)),"f" => null), 
	                array("v" => number_format($res->total_payments,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		} elseif ($years < 1 && $months >= 2 ) {

			$this->db->select('DATE_FORMAT(created_on, "%Y-%m") AS "payment_month", COALESCE(SUM(payment_amount),0) AS "total_payments"');
			$this->db->from('pos_payments');
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
	        $this->db->where(array('is_void' => 0));
			$this->db->group_by('DATE_FORMAT(created_on, "%Y-%m")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Day", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "POS Payments", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('M d Y', strtotime($res->payment_month)),"f" => null), 
	                array("v" => number_format($res->total_payments,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		} elseif ($years >= 1) {

			$this->db->select('DATE_FORMAT(created_on, "%Y") AS "payment_year", COALESCE(SUM(payment_amount),0) AS "total_payments"');
			$this->db->from('pos_payments');
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
	        $this->db->where(array('is_void' => 0));
			$this->db->group_by('DATE_FORMAT(created_on, "%Y")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Year", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "POS Payments", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('Y', strtotime($res->payment_year)),"f" => null), 
	                array("v" => number_format($res->total_payments,2,".",""),"f" => null) 
	            ); 
	        }
	        return $response;
		}

    }

    function get_online_payments_chart_data(){
		$date_from = $this->input->post('date_from');
    	$date_to = $this->input->post('date_to');

		$diff = abs(strtotime($date_to) - strtotime($date_from));

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		if ($years < 1 && $months < 2 ) {

			$this->db->select('DATE_FORMAT(transaction_time, "%Y-%m-%d") AS "payment_date", COALESCE(SUM(transaction_amount),0) AS "total_payments"');
			$this->db->from('paybill_payments');
			$this->db->where('DATE_FORMAT(transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
	        $this->db->where(array('ord_order_number != ' => ''));
			$this->db->group_by('DATE_FORMAT(transaction_time, "%Y-%m-%d")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Day", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "Online Payments", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('M d Y', strtotime($res->payment_date)),"f" => null), 
	                array("v" => number_format($res->total_payments,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		} elseif ($years < 1 && $months >= 2 ) {

			$this->db->select('DATE_FORMAT(transaction_time, "%Y-%m") AS "payment_month", COALESCE(SUM(transaction_amount),0) AS "total_payments"');
			$this->db->from('paybill_payments');
			$this->db->where('DATE_FORMAT(transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
	        $this->db->where(array('ord_order_number != ' => ''));
			$this->db->group_by('DATE_FORMAT(transaction_time, "%Y-%m")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Day", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "Online Payments", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('M d Y', strtotime($res->payment_month)),"f" => null), 
	                array("v" => number_format($res->total_payments,2,".",""),"f" => null) 
	            ); 
	        }

	        return $response;
		} elseif ($years >= 1) {

			$this->db->select('DATE_FORMAT(transaction_time, "%Y") AS "payment_year", COALESCE(SUM(transaction_amount),0) AS "total_payments"');
			$this->db->from('paybill_payments');
			$this->db->where('DATE_FORMAT(transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
			$this->db->where('DATE_FORMAT(transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
	        $this->db->where(array('ord_order_number != ' => ''));
			$this->db->group_by('DATE_FORMAT(transaction_time, "%Y")');

			$result = $this->db->get()->result();

			$response->cols[] = array( 
	            "id" => "", 
	            "label" => "Year", 
	            "pattern" => "", 
	            "type" => "string" 
	        ); 
	        $response->cols[] = array( 
	            "id" => "", 
	            "label" => "Online Payments", 
	            "pattern" => "", 
	            "type" => "number" 
	        ); 	

	        foreach($result as $res){ 
	            $response->rows[]["c"] = array( 
	                array("v" => date('Y', strtotime($res->payment_year)),"f" => null), 
	                array("v" => number_format($res->total_payments,2,".",""),"f" => null) 
	            ); 
	        }
	        return $response;											
		}

    }

    function get_pos_payments() {

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $payment_method = $this->input->post('payment_method');

		$this->db->select("*");
        $this->db->from('pos_payments pp');
        $this->db->where(array('pp.is_void' => 0));
        if ($payment_method != ''){
      		$this->db->where( array('pp.payment_method' => $payment_method));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $this->db->order_by("pp.pos_payment_id", "desc");
        return  $this->db->get()->result();
    }

    function get_online_payments() {

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

    	$this->db->select("*");
        $this->db->from('paybill_payments pp');
        //$this->db->where(array('pp.ord_order_number != ' => ''));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(pp.transaction_time, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        return  $this->db->get()->result();
    }

    function get_expenses_report(){

    	$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $outlet_id = $this->input->post('outlet_id');
        $system_user_id = $this->input->post('system_user_id');
        $status = $this->input->post('status');

    	$this->db->select("e.*, o.outlet_name, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('expenses e');  
        $this->db->join('outlets o', 'o.outlet_id = e.outlet_id', 'left outer');
        $this->db->join('system_users su', 'su.system_user_id = e.system_user_id', 'left outer');

        if ($status != ''){
        	$this->db->where(array('e.is_void' => $status));
        }

        if ($outlet_id != ''){
      		$this->db->where( array('e.outlet_id' => $outlet_id));
        }
        if ($system_user_id != ''){
      		$this->db->where( array('e.system_user_id' => $system_user_id));
        }

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(e.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(e.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $this->db->order_by("e.expense_id", "desc");
        return  $this->db->get()->result();
    }

    function get_total_expenses(){

    	$total_expenses = 0;

		$date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        $this->db->select("COALESCE(SUM(expense_amount),0) AS 'total_expenses'");
        $this->db->from('expenses');
        $this->db->where(array('is_void' => 0));

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(expense_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_to != ''){
            $this->db->where('DATE_FORMAT(expense_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        $result =  $this->db->get()->result();
        foreach ($result as $row) {
            $total_expenses = $row->total_expenses;
        }

        return $total_expenses;
    }

   



}