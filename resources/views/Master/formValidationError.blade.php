@if ($errors->has($param))
    <p style="color:red;font-weight:300;font-size:14px">
        <strong>{{ $errors->first($param) }}</strong>
    </p>
@endif
