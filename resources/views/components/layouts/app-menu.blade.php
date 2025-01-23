@can('dashboard-page')
    <x-menu-item title="Dashboard" icon="o-home" link="{{ route('dashboard') }}" wire:navigate />
@endcan
@can('master-data')
    <x-menu-sub title="Master Data" icon="o-circle-stack">
        @can('bank-page')
        <x-menu-item title="Banks" icon="o-building-library" link="{{ route('banks') }}" wire:navigate />
        @endcan
        @can('affiliate-page')
        <x-menu-item title="Affiliates" icon="o-users" link="{{ route('affiliates') }}" wire:navigate />
        @endcan
        @can('user-page')
        <x-menu-item title="Users" icon="o-users" link="{{ route('users') }}" wire:navigate />
        @endcan
        {{-- <x-menu-item title="Wifi" icon="o-wifi" link="####" />
        <x-menu-item title="Archives" icon="o-archive-box" link="####" /> --}}
    </x-menu-sub>
@endcan
@can('log-page')
    <x-menu-item title="Logs Activity" icon="o-home" link="{{ route('logs') }}" wire:navigate />
@endcan
