<x-dashboard-layout
    title="Menu & Pricing - PlatePal"
    heading="Menu & Pricing"
    :username="$displayName"
    :usersub="$user->barangay ?? ''"
    :initials="$initials"
>
    <x-slot:sidebar>
        @include('caterer.partials.menu-pricing.sidebar')
    </x-slot:sidebar>

    @php
        $menuTypes = [
            'packages' => [
                'label' => 'Packages',
                'singular' => 'Package',
                'count' => $menuSummary['packages'],
                'subtitle' => 'Client-ready offers',
                'icon' => 'packages',
                'accent' => 'bg-[#E8642A]',
                'iconWrap' => 'bg-[#FEF3EC]',
                'iconClass' => 'size-5 stroke-[#E8642A]',
                'entries' => $packages,
                'emptyTitle' => 'No packages yet',
                'emptyCopy' => 'Create a package clients can book directly.',
                'emptyButton' => 'Add Package',
                'editRoute' => 'menu.packages.edit',
                'destroyRoute' => 'menu.packages.destroy',
                'deletePrompt' => 'Delete this package?',
                'pageName' => 'packages_page',
            ],
            'items' => [
                'label' => 'Menu Items',
                'singular' => 'Menu Item',
                'count' => $menuSummary['items'],
                'subtitle' => 'Individual dishes',
                'icon' => 'items',
                'accent' => 'bg-[#c09049]',
                'iconWrap' => 'bg-[#F6EFE7]',
                'iconClass' => 'size-5 stroke-[#1C1A17]',
                'entries' => $menuItems,
                'emptyTitle' => 'No menu items yet',
                'emptyCopy' => 'Add dishes that clients can browse on your profile.',
                'emptyButton' => 'Add Menu Item',
                'editRoute' => 'menu.items.edit',
                'destroyRoute' => 'menu.items.destroy',
                'deletePrompt' => 'Delete this item?',
                'pageName' => 'items_page',
            ],
            'addons' => [
                'label' => 'Add-ons',
                'singular' => 'Add-on',
                'count' => $menuSummary['addons'],
                'subtitle' => 'Extras clients can add',
                'icon' => 'addons',
                'accent' => 'bg-[#d18408]',
                'iconWrap' => 'bg-[#F6EFE7]',
                'iconClass' => 'size-5 stroke-[#8A6D3F]',
                'entries' => $addOns,
                'emptyTitle' => 'No add-ons yet',
                'emptyCopy' => 'Create optional extras for booking requests.',
                'emptyButton' => 'Add Add-on',
                'editRoute' => 'menu.addons.edit',
                'destroyRoute' => 'menu.addons.destroy',
                'deletePrompt' => 'Delete this add-on?',
                'pageName' => 'addons_page',
            ],
        ];
    @endphp

    <div
        class="max-w-7xl mx-auto"
        x-data="{
            tab: @js($activeTab),
            showForm: false,
            formType: @js($activeTab),
            showDeleteModal: false,
            deleteTarget: {
                formId: '',
                name: '',
                type: ''
            },
            labels: {
                packages: @js($menuTypes['packages']['singular']),
                items: @js($menuTypes['items']['singular']),
                addons: @js($menuTypes['addons']['singular'])
            },
            openForm(type = this.tab) {
                this.formType = type;
                this.showForm = true;
            },
            addButtonLabel() {
                return 'Add ' + this.labels[this.tab];
            },
            openDeleteModal(formId, name, type) {
                this.deleteTarget = { formId, name, type };
                this.showDeleteModal = true;
            },
            closeDeleteModal() {
                this.showDeleteModal = false;
                this.deleteTarget = { formId: '', name: '', type: '' };
            }
        }"
    >
        @if(session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-5">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black text-[#1C1A17] mb-2">Menu & Pricing</h1>
                <p class="text-sm sm:text-base text-[#8A6D3F]">Keep client-ready packages, dishes, and extras easy to manage.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2.5">
                <a href="{{ route('caterer.profile') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-[#EDE4D8] bg-white text-sm font-bold text-[#1C1A17] hover:bg-[#FDF6EE] transition-colors">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'edit', 'class' => 'size-4 stroke-[#8A7F72]'])
                    Profile
                </a>
                <button @click="openForm()" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#E8642A] text-white text-sm font-bold hover:bg-[#F07C42] transition-colors shadow-sm">
                    @include('caterer.partials.menu-pricing.icon', ['name' => 'addons', 'class' => 'size-4 stroke-white'])
                    <span x-text="addButtonLabel()"></span>
                </button>
            </div>
        </div>

        <div class="grid xl:grid-cols-[minmax(0,1fr)_360px] gap-4 mb-5">
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach($menuTypes as $tabKey => $menuType)
                    @include('caterer.partials.menu-pricing.summary-card', [
                        'tabKey' => $tabKey,
                        'menuType' => $menuType,
                        'activeTab' => $activeTab,
                    ])
                @endforeach
            </div>

            @include('caterer.partials.menu-pricing.status-overview', ['menuSummary' => $menuSummary])
        </div>

        @include('caterer.partials.menu-pricing.filter-bar', ['activeTab' => $activeTab])
        @include('caterer.partials.menu-pricing.tab-nav', ['menuTypes' => $menuTypes, 'activeTab' => $activeTab])

        @foreach($menuTypes as $tabKey => $menuType)
            @include('caterer.partials.menu-pricing.menu-section', [
                'tabKey' => $tabKey,
                'menuType' => $menuType,
            ])
        @endforeach

        @include('caterer.partials.menu-pricing.modal')
        @include('caterer.partials.menu-pricing.delete-modal')
    </div>
</x-dashboard-layout>
