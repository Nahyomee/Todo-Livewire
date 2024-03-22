<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class EditCategory extends Component
{
    public $category;

    public $name;

    public $colour;

    #[Title('Edit Category')]
    #[Layout('components.layouts.app')]

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->category;
        $this->colour = $category->colour;
    }

    public function editList()
    {

        $this->authorize('update', $this->category);
        $validated = $this->validate([
            'name' => ['required', 'min:3',
            Rule::unique('categories', 'category')->where(fn ($query) => $query->where('user_id', auth()->user()->id))],
            'colour' => ['required']
            ], [
            'name.required' => 'You need to input a name!',
            'name.min' => 'The name is too short!',
            'name.unique' => 'Name already taken!',
            'colour.required' => 'You need to input a colour!',
        ]);
        $this->update($validated);

    }

    protected function update($validated)
    {
        $this->category->update([
            'category' => $validated['name'],
            'colour' => $validated['colour']
        ]);
        session()->flash('success', 'List updated');
        $this->dispatch('category-changed');
    }

}
