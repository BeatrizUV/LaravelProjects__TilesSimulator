<div style="display: block; width: 100%; padding: 2% 0 2% 0; margin: 0; background-color: #DDD;">
    <div style="display: block; margin: auto; width: 800px; background-color: #FFF;">
        @if (!$showroom)
            {{--*/ $colour = '#CCCCCC' /*--}}
            <img title="" src="{{ $appUrl }}/images/email/header.jpg" alt="" width="800" height="375" style="display: block; clear: both; margin: 0;" />
        @else
            {{--*/ $colour = '#999' /*--}}
            <img title="{{ $showroom->company }}" src="{{ $appUrl }}/images/email/header-dist.jpg" alt="{{ $showroom->company }}" width="800" height="375" style="display: block; clear: both; margin: 0;" />
        @endif
        <div style="display: block; width: 90%; clear: both; padding: 0 3.5% 5% 3.5%; margin-top: 0;">
            <h1 style="width: 100%; color: {{ $colour }}; font-size: 26px; text-align: center;">{{ trans('email.title') }}</h1>
            <table style="width: 100%; border: none; vertical-align: top; margin-bottom: 20px;" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding: 0 5px 0 5px;" valign="top">
                        <div style="display: block; width: 255px; margin-right: 5%; clear: left; border: 1px solid #CCC; padding: 10px;">
                            <h2 style="text-align: center; font-size: 20px; color: #000;">{{ $plaque->name }}<br />
                                <small style="text-align: center; font-size: 14px; color: #000;">({{ $plaque->format }})</small></h2>
                            <img title="{{ $plaque->name }}" src="{{ $message->embed($plaque->thumbnail) }}" alt="{{ $plaque->name }}" width="250" height="250" style="border: 1px solid #000;" />
                            <h3 style="text-align: center; font-size: 15px; color: #000;">{{ trans('budget.labels.quantity') }}: {{ $quantity }} uds</h3>
                            <table cellpadding="0" cellspacing="0" style="width: 100%; display: table;">
                                <thead style="background-color: {{ $colour }};">
                                    <tr style="height: 25px;">
                                        <td style="border: 1px solid #CCC; text-align: center; font-size: 16px; color: #FFF;">{{ strtoupper(trans('email.pieces.name')) }}</td>
                                        <td style="border: 1px solid #CCC; text-align: center; font-size: 16px; color: #FFF;">{{ strtoupper(trans('budget.labels.pieces.colour')) }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($plaque->piecesList as $piece)
                                    <tr style="display: table-row;">
                                        <td style="border: 1px solid #CCC; vertical-align: middle; font-size: 15px; line-height: 30px; padding: 2px; display: table-cell;">
                                            <img style="margin-bottom: -9px;" title="{{ $piece->name }}" src="{{ $appUrl . '_data/' . $piecesPath . '/' . $piece->image }}" alt="{{ $piece->name }}" width="30" height="30" /> {{ $piece->name }}
                                        </td>
                                        <td style="border: 1px solid #CCC; font-size: 15px; line-height: 30px; padding: 2px; display: table-cell;">
                                            <img style="border: 1px solid #000; margin-bottom: -9px;" title="{{ $piece->colour }}" src="{{ $appUrl . '_data/' . $coloursPath . '/' . $piece->colour . '.jpg' }}" alt="{{ $piece->colour }}" width="30" height="30" /> {{ $piece->colour }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td style="padding: 0 5px 0 5px;" valign="top">
                        <div style="display: block; width: 425px; padding: 10px; clear: right; border: 1px solid #CCC;">
                            <h2 style="text-align: center; font-size: 20px; color: #000;">{{ trans('email.customer-data.title') }}</h2>
                            <p style="padding-bottom: 10px; border-bottom: 1px solid #CCC; margin-bottom: 10px;">
                                <label style="display: inline-block; width: 100px;">{{ trans('budget.labels.name') }}:</label> {{ $customer['name'] }}
                            </p>
                            <p style="padding-bottom: 10px; border-bottom: 1px solid #CCC; margin-bottom: 10px;">
                                <label style="display: inline-block; width: 100px;">{{ trans('budget.labels.email') }}:</label> {{ $customer['email'] }}
                            </p>
                            <p style="padding-bottom: 10px; border-bottom: 1px solid #CCC; margin-bottom: 10px;">
                                <label style="display: inline-block; width: 100px;">{{ trans('budget.labels.telephone') }}:</label> {{ $customer['telephone'] }}
                            </p>
                            <p style="padding-bottom: 10px; border-bottom: 1px solid #CCC; margin-bottom: 10px;">
                                <label style="display: inline-block; width: 100px;">{{ trans('budget.labels.location') }}:</label> {{ $customer['location'] }}
                            </p>
                            <p style="padding-bottom: 10px; border-bottom: 1px solid #CCC; margin-bottom: 10px;">
                                <label style="display: inline-block; width: 100px;">{{ trans('budget.labels.country') }}:</label> {{ $customer['country'] }}
                            </p>
                            <p style="padding-bottom: 10px; border-bottom: 1px solid #CCC; margin-bottom: 10px;">
                                <label style="display: inline-block; width: 100px;">{{ trans('budget.labels.comments') }}:</label> {{ $comments }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
            <hr />
            @if (!$showroom)
                <p style="color: {{ $colour }}; text-align: center; font-size: 12px;">{{ env('APP_URL') }} | {{ env('MAIL_USERNAME') }} | {{ env('TELEPHONE') }}</p>
            @else
                <p style="color: {{ $colour }}; text-align: center; font-size: 12px;">{{ str_replace('http://', '', $showroom->website) }} | {{ $showroom->email }} | {{ $showroom->telephone }}</p>
            @endif
        </div>
    </div>
</div>
