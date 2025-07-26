<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use App\Models\Product;

class ProductIndex extends Component
{
    public $products, $name, $detail, $productId;
    public $isOpen = false;
    public $isEdit = false;
    public $isShow = false;
    public $confirmingDelete = false;
    public function render()
    {
        $this->products = Product::latest()->get();
        return view('livewire.admin.products.product-index');
    }

    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
        $this->isEdit = false;
    }

    public function openEditModal($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->detail = $product->detail;
        $this->isOpen = true;
        $this->isEdit = true;

        session()->flash('message', 'Product saved successfully.');
    }

    public function openShowModal($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->detail = $product->detail;
        $this->isShow = true;
    }

    public function confirmDelete($id)
    {
        $this->productId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        Product::findOrFail($this->productId)->delete();
        $this->confirmingDelete = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
        ]);

        Product::updateOrCreate(['id' => $this->productId], ['name' => $this->name, 'detail' => $this->detail]);

        session()->flash('message', 'Product saved successfully.');

        $this->resetInputFields();
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->detail = '';
        $this->productId = null;
        $this->isShow = false;
        $this->isOpen = false;
        $this->isEdit = false;
        $this->confirmingDelete = false;
    }
}
