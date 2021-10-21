<div class="col-md-2 szd-logo">
@if ($dist)
    <img title="{{ trans('simulator.header.logo.title') }} | {{ $showroom->company }}" src="/_data/distribuidores/{{ $showroom->logo }}" alt="{{ trans('simulator.header.logo.title') }} | {{ $showroom->company }}" width="200" height="90" />
@else
    <img title="{{ trans('simulator.header.logo.title') }}" src="/images/logo.png" alt="{{ trans('simulator.header.logo.title') }}" width="200" height="90" />
@endif
</div>
