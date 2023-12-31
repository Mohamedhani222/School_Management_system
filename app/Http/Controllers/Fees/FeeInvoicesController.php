<?php

namespace App\Http\Controllers\Fees;

use App\Http\Controllers\Controller;
use App\interfaces\FeesInvoicesRepositoryInterface;
use App\Models\Fee;
use App\Models\FeeInvoices;
use Illuminate\Http\Request;

class FeeInvoicesController extends Controller
{
    public FeesInvoicesRepositoryInterface $fees_invoices;

    public function __construct(FeesInvoicesRepositoryInterface $fees_invoices)
    {
        $this->fees_invoices = $fees_invoices;

    }

    public function index()
    {
        return $this->fees_invoices->index();
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        return $this->fees_invoices->store($request);
    }

    public function show($id)
    {
        return $this->fees_invoices->show($id);

    }

    public function edit($id)
    {
        return $this->fees_invoices->edit($id);
    }

    public function update(Request $request)
    {
        return $this->fees_invoices->update($request);
    }

    public function destroy(Request $request)
    {
        return $this->fees_invoices->destroy($request);
    }

}
