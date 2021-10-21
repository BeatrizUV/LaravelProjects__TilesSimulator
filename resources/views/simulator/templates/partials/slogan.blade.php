<div class="col-md-8 szd-slogan">
    <div class="row szd-slogan">
        <h1 class="text-center">{{ trans('simulator.header.slogan') }}</h1>
    </div>
    <div class="row szd-telephone">
        <h3 class="text-center"><span class="glyphicon glyphicon-phone-alt"></span> 
        @if ($dist)
            &nbsp;{{ $showroom->telephone }}
        @else
            &nbsp;+34 954 21 21 24
        @endif
        </h3>
    </div>
</div>