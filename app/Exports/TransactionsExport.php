<?php

namespace App\Exports;

use App\Models\Transaction;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use OpenSpout\Writer\CSV\Writer as CsvWriter;
use OpenSpout\Common\Entity\Row;

class TransactionsExport
{
    protected $dateFrom;
    protected $dateTo;

    public function __construct($dateFrom = null, $dateTo = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function query()
    {
        $query = Transaction::with(['product.category', 'user']);

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->latest();
    }

    protected function rows(): \Generator
    {
        yield Row::fromValues([
            'ID', 'Produk', 'Kategori', 'Customer', 'Email', 'Tipe', 'Qty', 'Total', 'Tanggal', 'Catatan'
        ]);

        foreach ($this->query()->cursor() as $tx) {
            yield Row::fromValues([
                $tx->id,
                $tx->product->name,
                $tx->product->category->name ?? '-',
                $tx->user?->name ?? '-',
                $tx->user?->email ?? '-',
                $tx->type === 'sale' ? 'Penjualan' : 'Pembelian',
                $tx->quantity,
                $tx->total,
                $tx->created_at->format('d/m/Y H:i'),
                $tx->notes ?? '',
            ]);
        }
    }

    public function downloadXlsx()
    {
        $writer = new XlsxWriter();
        $temp = tempnam(sys_get_temp_dir(), 'tx_');
        $writer->openToFile($temp);

        foreach ($this->rows() as $row) {
            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($temp, 'transactions.xlsx')->deleteFileAfterSend();
    }

    public function downloadCsv()
    {
        $writer = new CsvWriter();
        $temp = tempnam(sys_get_temp_dir(), 'tx_');
        $writer->openToFile($temp);

        foreach ($this->rows() as $row) {
            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($temp, 'transactions.csv')->deleteFileAfterSend();
    }
}
