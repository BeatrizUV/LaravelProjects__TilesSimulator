<div class="row szd-return pull-right">
@if ($dist)
    <a title="{{ trans('simulator.header.return.title') }} {{ ucfirst(str_replace('http://www.', '', $showroom->website)) }}" href="{{ $showroom->website }}">
        <img title="{{ trans('simulator.header.return.title') }} {{ ucfirst(str_replace('http://www.', '', $showroom->website)) }}" src="/images/volver.png" alt="{{ trans('simulator.header.return.title') }} {{ ucfirst(str_replace('http://www.', '', $showroom->website)) }}" width="120" height="50" /><br /><span>{{ str_replace('http://www.', '', $showroom->website) }}</span>
    </a>
@else
<a title="{{ trans('simulator.header.return.title') }}" href="">
        <img title="{{ trans('simulator.header.return.title') }}" src="/images/volver.png" alt="{{ trans('simulator.header.return.title') }} {{ env('WEB_NAME') }}" width="120" height="50" /><br /><span>{{ env('APP_URL') }}</span>
    </a>
@endif
</div>
