<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Repositories\Contracts\SupplierRepositoryInterface;

class SupplierController extends Controller
{
    protected $supplierRepository;

    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function index()
    {
        $suppliers = $this->supplierRepository->getAll();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->supplierRepository->create($request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show($id)
    {
        $supplier = $this->supplierRepository->findById($id);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = $this->supplierRepository->findById($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        $this->supplierRepository->update($id, $request->validated());
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $this->supplierRepository->delete($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
