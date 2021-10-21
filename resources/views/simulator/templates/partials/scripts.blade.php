{!! "<script>var alertColours = '" . trans('messages.warnings.select-colour') . "';</script>" !!}
{!! "<script>var piecesTableColour = '" . trans('budget.labels.pieces.colour') . "';</script>" !!}
{!! "<script>var piecesTableName = '" . trans('budget.labels.pieces.name') . "';</script>" !!}
{!! "<script>var plaqueLabel = '" . trans('budget.labels.plaque') . "';</script>" !!}
{!! "<script>var fatalError = '" . trans('messages.fatal.global-error') . "';</script>" !!}
{!! Html::script('/js/frontend/scripts.min.js') !!}
{!! Html::script('/js/frontend/svg2png/saveSvgAsPng.min.js') !!}