<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


/*
  | -----------------------------------------------------
  | PRODUCT NAME: 	SCHOOL MANAGER
  | -----------------------------------------------------
  | AUTHER:			MIAN SALEEM
  | -----------------------------------------------------
  | EMAIL:			saleem@tecdiary.com
  | -----------------------------------------------------
  | COPYRIGHTS:		RESERVED BY TECDIARY IT SOLUTIONS
  | -----------------------------------------------------
  | WEBSITE:			http://tecdiary.net
  | -----------------------------------------------------
  |
  | MODULE: 			logging
  | -----------------------------------------------------
  | This is logging module model file.
  | -----------------------------------------------------
 */

class logging_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllProducts() {
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllUsers() {
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function clearLogs($value) {
        //var_dump(strlen($value));
        if (strlen($value) >= 3) {
            for ($i = 0; $i < 3; $i++) {
                if ($value[$i] == 1) {
                    switch ($i) {
                        case 0:
                            $this->db->where('record_type', 1)
                                    ->or_where('record_type', 2);
                            break;
                        case 1:
                            $this->db->or_where('record_type', 3);
                            break;
                        case 2:
                            $this->db->or_where('record_type', 4);
                            break;
                    }
                }
            }
            if ($this->db->delete('logs')) {
                return TRUE;
            } else
                return FALSE;
        }
        return FALSE;
    }

    public function getAllCategories() {
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getStockValue() {
        $this->db->select('sum(warehouses_products.quantity)*price as stock_by_price, sum(warehouses_products.quantity)*cost as stock_by_cost');
        $this->db->from('products');
        $this->db->join('warehouses_products', 'warehouses_products.product_id=products.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getWarehouseStockValue($id) {
        $this->db->select('sum(warehouses_products.quantity)*price as stock_by_price, sum(warehouses_products.quantity)*cost as stock_by_cost');
        $this->db->from('products');
        $this->db->join('warehouses_products', 'warehouses_products.product_id=products.id');
        $this->db->where('warehouses_products.warehouse_id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getmonthlyPurchases() {
        $myQuery = "SELECT (CASE WHEN date_format( date, '%b' ) Is Null THEN 0 ELSE date_format( date, '%b' ) END) as month, SUM( COALESCE( total, 0 ) ) AS purchases FROM purchases WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH ) GROUP BY date_format( date, '%b' ) ORDER BY date_format( date, '%m' ) ASC";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getChartData() {
        $myQuery = "SELECT S.month,
                    S.added,
                    S.modified,
                    S.deleted,
                    S.sent
                    FROM (SELECT date_format(access_time, '%Y-%m') Month,
                    SUM(IF(altered='1',1,0)) added,
                    SUM(IF(altered='2',1,0)) modified,
                    SUM(IF(altered='3',1,0)) deleted,
                    SUM(IF(altered='4',1,0)) sent
                    FROM logs
                    WHERE logs.access_time >= date_sub( now( ) , INTERVAL 12 MONTH )
                    GROUP BY date_format(access_time, '%Y-%m')) S  
                    GROUP BY S.Month
                    ORDER BY S.Month";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    /* public function getDailySales() 
      {
      $year = '2013'; $month = '3';
      $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( total, 0 ) ) AS sales, SUM( COALESCE( total_tax, 0 ) ) as tax1, SUM( COALESCE( total_tax2, 0 ) ) as tax2
      FROM sales
      WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '2013-4'
      GROUP BY DATE_FORMAT( date,  '%e' )";
      $q = $this->db->query($myQuery);
      if($q->num_rows() > 0) {
      foreach (($q->result()) as $row) {
      $data[] = $row;
      }

      return $data;
      }
      } */

    public function getAllWarehouses() {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllCustomers() {
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllBillers() {
        $q = $this->db->get('billers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllSuppliers() {
        $q = $this->db->get('suppliers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getDailySales($year, $month) {

        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( total_tax, 0 ) ) AS tax1, SUM( COALESCE( total_tax2, 0 ) ) AS tax2, SUM( COALESCE( total, 0 ) ) AS total, SUM( COALESCE( inv_discount, 0 ) ) AS discount
			FROM sales
			WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
			GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getMonthlySales($year) {

        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( total_tax, 0 ) ) AS tax1, SUM( COALESCE( total_tax2, 0 ) ) AS tax2, SUM( COALESCE( total, 0 ) ) AS total
			FROM sales
			WHERE DATE_FORMAT( date,  '%Y' ) =  '{$year}' 
			GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

}
