@extends('layouts.main')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/balance/style.css') }}">
    @include('partials.header')
    @include('partials.sidebar')
    {{-- <h1>diri na ang form nga mahimo</h1> --}}
    <div class="card">
        {{-- for header only --}}
        <div class="card-header">
            <h1>WALK IN</h1>
        </div>
        {{-- for the body --}}
        <div class="card-body">
            <form action="{{ route('balance.store', $payment->id) }}" method="POST" class="row g-3 mt-2 ">
                @csrf
                <div class="form-floating ">
                    <input name="sbp_description" type="text" value="{{ $payment->description->name }}"
                        class="form-control" id="floatingInput" placeholder="Description" readonly />
                    <label for="floatingInput">Description</label>
                </div>

                <div class="form-floating">
                    <input name="sbp_amount" type="text" value="{{ $payment->amount }}" class="form-control"
                        id="amount" placeholder="Amount" readonly />
                    <label for="floatingPassword">Amount</label>
                </div>
                <div class="form-floating">
                    <input name="sbp_balance_amount" type="text" value="" class="form-control" id="sbp_d"
                        placeholder="Amount">
                    <label for="floatingPassword">Balance Amount</label>
                </div>
                <div class="form-floating">
                    <input name="sbp_paid_amount" type="text" class="form-control" id="amount" placeholder="Amount">
                    <label for="floatingPassword">Paid Amount</label>
                </div>
                <div class="form-floating">
                    <input name="sbp_paid_change" type="text" class="form-control" id="amount" placeholder="Amount">
                    <label for="floatingPassword">Paid Change</label>
                </div>

                <div class="form-floating">
                    <select name="status" class="form-select" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option value="Complete">Complete</option>
                        <option value="Pending">Pending</option>
                    </select>
                    <label for="floatingSelect">Status</label>
                </div>

                <button type="submit" class="btn btn-outline-danger">Pay</button>

            </form>
        </div>
    </div>
@endsection
{{-- 
<script>
    constamountInput = document.getElementById('amount');
    let amountValue = '';

    amountInput.addEventListener('input', function(event) {
        amountValue = event.target.value;
    });

    amountInput.addEventListener('focus', function(event) {
        event.preventDefault();

        // Set the value to the peso sign ('₱') and append the stored amountValue
        amountInput.value = amountValue ? `₱${amountValue}` : '₱';

        // Set the maximum length of the input to 14 characters (including the peso sign)
        amountInput.maxLength = 14;
    });
</script> --}}
