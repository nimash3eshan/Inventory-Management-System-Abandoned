<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function saveDailyReport($input)
    {
        if(!empty($input['prev_balance'])) {
            $this->prev_balance = $input['prev_balance'];
        } else {
            $prev_report  = $this->getAll('last');
            $this->prev_balance = !empty($prev_report) ? $prev_report->net_balance : setting('starting_balance');
        }
        
        $this->total_sales = $input['total_sales'];
        $this->total_payment = $input['total_payment'];
        $this->total_dues = $input['total_dues'];
        $this->sale_profit = $input['sale_profit'];
        $this->total_income = $input['total_income'];
        $this->total_expense = $input['total_expense'];
        $this->total_receivings = $input['total_receivings'];
        $this->total_receivings_payment = $input['total_receivings_payment'];
        $this->total_receivings_dues = $input['total_receivings_dues'];
        $this->total_supplier_payment = $input['total_supplier_payment'];
        // Calculate Total Costing
        $input['total_costing'] = $input['total_expense'] + $input['total_receivings_payment'] + $input['total_supplier_payment'];
        $this->total_costing = $input['total_costing'];
         // Calculate  Total Profit
         $input['total_profit'] = $input['total_income'] - $input['total_costing'];
         $this->total_profit = $input['total_profit'];
        // Calculate Net Balance    
        $input['net_balance'] = $this->prev_balance + $input['total_profit'];
        $this->net_balance = $input['net_balance'];
       
        $this->user_id = $input['user_id'];
        $this->created_at = date('Y-m-d h:i:s', strtotime($input['date']));
        if ($this->save()) {
            return true;
        }
        return false;
    }

    public function getAll($option=null, $search=null) {
        $results = $this;
        $per_page = !empty($search['per_page']) ? $search['per_page'] : 10;
        if(!empty($search['start_date']) || !empty($search['end_date'])) {
            $start_date = !empty($search['start_date']) ? $search['start_date'] : date('Y-m-d');
            $end_date = !empty($search['end_date']) ? $search['end_date'] : date('Y-m-d');
            $results = $results->whereBetween('created_at', [ $start_date.' 00:00:00', $end_date.' 23:59:59']);
        }
        if($option=='last') {
            $results = $results->latest()->first();
        } else if ($option == 'paginate') {
            $results = $results->latest()->paginate($per_page);
        } else {
            $results = $results->latest()->get();
        }
        return $results;
    }

    public function getByDate($date) {
        return $this->whereDate('created_at', '=', $date)->first();
    }

    public function getById($id)
    {
        return $this->findOrFail($id);
    }
}
