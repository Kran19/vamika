@extends('layouts.admin')

@php
$pageConfig = [
    'title' => 'Category Management',
    'subtitle' => 'Product Brand & Classification Lines',
    'role' => 'Admin',
    'showBottomNav' => true
];
@endphp

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">

    <!-- Top Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition-colors">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.products.index') }}" class="hover:text-slate-600 transition-colors">Products</a>
                <span>/</span>
                <span class="text-indigo-600">Categories</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Product Categories</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Define brand lines and categories available for orders and catalogs.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}"
               class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center gap-2 shadow-sm">
                <iconify-icon icon="lucide:package" width="16"></iconify-icon>
                <span>All Products</span>
            </a>
            <button type="button" onclick="openCreateModal()"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-100 hover:shadow-indigo-200 active:scale-95">
                <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                <span>Add Category</span>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Categories -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <iconify-icon icon="lucide:tags" width="20"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</span>
            </div>
            <div class="text-2xl font-bold text-slate-900 font-outfit">{{ $stats['total'] }}</div>
            <p class="text-[11px] font-medium text-slate-500 mt-1">Configured Categories</p>
        </div>

        <!-- Active Categories -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <iconify-icon icon="lucide:check-circle" width="20"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Active</span>
            </div>
            <div class="text-2xl font-bold text-slate-900 font-outfit">{{ $stats['active'] }}</div>
            <p class="text-[11px] font-medium text-slate-500 mt-1">Live in ordering tabs</p>
        </div>

        <!-- Inactive Categories -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <iconify-icon icon="lucide:alert-circle" width="20"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Inactive</span>
            </div>
            <div class="text-2xl font-bold text-slate-900 font-outfit">{{ $stats['inactive'] }}</div>
            <p class="text-[11px] font-medium text-slate-500 mt-1">Hidden from ordering</p>
        </div>

        <!-- Categorized Products -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <iconify-icon icon="lucide:boxes" width="20"></iconify-icon>
                </div>
                <span class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">Catalog</span>
            </div>
            <div class="text-2xl font-bold text-slate-900 font-outfit">{{ $stats['products'] }}</div>
            <p class="text-[11px] font-medium text-slate-500 mt-1">Products Assigned</p>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <iconify-icon icon="lucide:search" width="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search category name, slug or description..."
                       class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all">
            </div>

            <!-- Status Filter -->
            <div class="flex items-center gap-1.5 p-1 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                <a href="{{ route('admin.categories.index', array_merge(request()->except('status'), ['status' => 'all'])) }}"
                   class="px-3 py-1.5 rounded-lg font-bold transition-all {{ (!request('status') || request('status') === 'all') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                    All
                </a>
                <a href="{{ route('admin.categories.index', array_merge(request()->except('status'), ['status' => 'active'])) }}"
                   class="px-3 py-1.5 rounded-lg font-bold transition-all {{ request('status') === 'active' ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                    Active
                </a>
                <a href="{{ route('admin.categories.index', array_merge(request()->except('status'), ['status' => 'inactive'])) }}"
                   class="px-3 py-1.5 rounded-lg font-bold transition-all {{ request('status') === 'inactive' ? 'bg-amber-50 text-amber-700 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                    Inactive
                </a>
            </div>

            @if(request('search') || (request('status') && request('status') !== 'all'))
                <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 flex items-center justify-center gap-1">
                    <iconify-icon icon="lucide:x" width="14"></iconify-icon> Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4">Category Name</th>
                        <th class="py-3.5 px-4">Slug Identifier</th>
                        <th class="py-3.5 px-4 text-center">Products</th>
                        <th class="py-3.5 px-4 text-center">Sort Order</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($categories as $index => $category)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $category->name }}</div>
                                @if($category->description)
                                    <div class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $category->description }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <code class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-mono text-xs font-semibold border border-slate-200">
                                    {{ $category->slug }}
                                </code>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $category->products_count > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-slate-50 text-slate-400 border border-slate-100' }}">
                                    <iconify-icon icon="lucide:package" width="12"></iconify-icon>
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-600">
                                {{ $category->sort_order }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('admin.categories.toggle-status', $category->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            title="Click to toggle status"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold transition-all {{ $category->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $category->status === 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ ucfirst($category->status) }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Edit Button -->
                                    <button type="button"
                                            onclick='openEditModal(@json($category))'
                                            class="h-8 w-8 rounded-lg bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 border border-slate-200 flex items-center justify-center transition-all"
                                            title="Edit Category">
                                        <iconify-icon icon="lucide:edit-3" width="14"></iconify-icon>
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button"
                                            onclick="confirmDeleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->products_count }})"
                                            class="h-8 w-8 rounded-lg bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-600 border border-slate-200 flex items-center justify-center transition-all"
                                            title="Delete Category">
                                        <iconify-icon icon="lucide:trash-2" width="14"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="h-16 w-16 rounded-2xl bg-slate-50 text-slate-300 flex items-center justify-center mb-3">
                                        <iconify-icon icon="lucide:tags" width="32"></iconify-icon>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800">No categories found</h3>
                                    <p class="text-xs text-slate-400 mt-1 mb-4">Get started by creating your first product category.</p>
                                    <button type="button" onclick="openCreateModal()"
                                            class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100">
                                        Add New Category
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= CREATE CATEGORY MODAL ================= -->
<div id="createCategoryModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-200">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <iconify-icon icon="lucide:plus" width="20"></iconify-icon>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Add New Category</h3>
                    <p class="text-xs text-slate-400">Create a new brand or product line</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()" class="h-8 w-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                <iconify-icon icon="lucide:x" width="18"></iconify-icon>
            </button>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-5 space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Category Name *</label>
                <input type="text" name="name" id="createName" required
                       oninput="autoGenerateSlug(this.value, 'createSlug')"
                       placeholder="e.g. Beverages, Snacks, Michi's..."
                       class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800">
            </div>

            <!-- Slug -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Slug Identifier *</label>
                    <span class="text-[10px] text-slate-400">Used internally in database</span>
                </div>
                <div class="relative">
                    <input type="text" name="slug" id="createSlug" required
                           placeholder="e.g. beverages, snacks..."
                           class="w-full px-4 py-2.5 text-sm font-mono bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all text-slate-700">
                </div>
            </div>

            <!-- Sort Order & Status -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" value="0" min="0"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Status *</label>
                    <select name="status" class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                <textarea name="description" rows="2" placeholder="Brief note about products in this category..."
                          class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-md shadow-indigo-100">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= EDIT CATEGORY MODAL ================= -->
<div id="editCategoryModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-200">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <iconify-icon icon="lucide:edit-3" width="20"></iconify-icon>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Edit Category</h3>
                    <p class="text-xs text-slate-400">Update category details and slug</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="h-8 w-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                <iconify-icon icon="lucide:x" width="18"></iconify-icon>
            </button>
        </div>

        <form id="editCategoryForm" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Category Name *</label>
                <input type="text" name="name" id="editName" required
                       class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800">
            </div>

            <!-- Slug -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Slug Identifier *</label>
                    <span class="text-[10px] text-amber-600 font-medium">Changing slug will auto-update linked products</span>
                </div>
                <input type="text" name="slug" id="editSlug" required
                       class="w-full px-4 py-2.5 text-sm font-mono bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all text-slate-700">
            </div>

            <!-- Sort Order & Status -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" id="editSortOrder" min="0"
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Status *</label>
                    <select name="status" id="editStatus" class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                <textarea name="description" id="editDescription" rows="2"
                          class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-md shadow-indigo-100">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteCategoryForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    function autoGenerateSlug(text, targetId) {
        const slug = text.toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById(targetId).value = slug;
    }

    function openCreateModal() {
        document.getElementById('createCategoryModal').classList.remove('hidden');
        document.getElementById('createName').focus();
    }

    function closeCreateModal() {
        document.getElementById('createCategoryModal').classList.add('hidden');
    }

    function openEditModal(category) {
        document.getElementById('editCategoryForm').action = `/admin/categories/${category.id}`;
        document.getElementById('editName').value = category.name || '';
        document.getElementById('editSlug').value = category.slug || '';
        document.getElementById('editSortOrder').value = category.sort_order ?? 0;
        document.getElementById('editStatus').value = category.status || 'active';
        document.getElementById('editDescription').value = category.description || '';

        document.getElementById('editCategoryModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });

    function confirmDeleteCategory(id, name, productsCount) {
        if (productsCount > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete Category',
                text: `"${name}" contains ${productsCount} product(s). Please reassign or delete the products first, or mark this category as Inactive.`,
                confirmButtonColor: '#4F46E5',
                confirmButtonText: 'Understand'
            });
            return;
        }

        Swal.fire({
            title: `Delete "${name}"?`,
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteCategoryForm');
                form.action = `/admin/categories/${id}`;
                form.submit();
            }
        });
    }
</script>
@endsection
