<div class="col-sm-4 mb-5">
    <div class="form-group">
        <label for="" class="form-label">ACCOUNT</label>
        <select class="form-control text-center" name="account_id" onchange='$("#{{ $form_name }}").submit()'>
            <option value="" selected>-- SELECT -- </option>
            @foreach ($bol_accounts as $id => $name)
                <option value="{{ $id }}" @if (request()->query('account_id') == $id) selected @endif>
                    {{ $name }}</option>
            @endforeach
        </select>
    </div>
</div>
