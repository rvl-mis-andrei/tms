<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DTServerSide
{
    protected $request;
    protected $data;
    public $rows;
    public $draw;
    public $recordsTotal;
    public $recordsFiltered;

    public function __construct(Request $request, Collection $data) {
        $this->request = $request;
        $this->data = $data;
    }

    public function renderTable(): void
    {
        $this->draw = $this->request->input('draw');
        $start = $this->request->input('start');
        $length = $this->request->input('length');
        $search = $this->request->input('search.value');
        $order_column_index = $this->request->input('order.0.column');
        $order_dir = $this->request->input('order.0.dir');
        $columns = $this->request->input('columns');

        $results = $this->data;

        // Apply search filter
        if ($search) {
            $results = $results->filter(function ($row) use ($search, $columns) {
                foreach ($columns as $column) {
                    if ($column['searchable'] === 'true' && stripos($row->{$column['name']}, $search) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // Apply order by
        $order_column_name = $columns[$order_column_index]['data'] ?? null;
        if ($order_column_name) {
            $results = $results->sortBy($order_column_name, SORT_NATURAL, $order_dir !== 'asc');
        }

        $this->recordsTotal = $this->data->count();
        $this->recordsFiltered = $results->count();

        $this->rows = $results->slice($start, $length)->values()->map(function ($row) use ($columns) {
            $data_row = [];
            foreach ($columns as $column) {
                $data_row[$column['name']] = $row->{$column['name']};
            }
            return $data_row;
        })->all();
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function getDraw(): int
    {
        return $this->draw;
    }

    public function getRecordsTotal(): int
    {
        return $this->recordsTotal;
    }

    public function getRecordsFiltered(): int
    {
        return $this->recordsFiltered;
    }
}
