@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h1>Loan Details</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary" onclick="window.location='{{ route('emiDetails') }}'">Emi Details</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
                <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Client Id</th>
                            <th scope="col">Number Of payment</th>
                            <th scope="col">First PaymentDate</th>
                            <th scope="col">Last Payment Date</th>
                            <th scope="col">Loan Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php
                              $incrementalId = 1; 
                          @endphp
              @if(count($loanDetails) > 0)
                          @foreach ($loanDetails as $loanDetail)
                              <tr>
                                  <td>{{ $incrementalId++ }}</td>
                                  <td>{{ $loanDetail->clientid }}</td>
                                  <td>{{ $loanDetail->num_of_payment }}</td>
                                  <td>{{ $loanDetail->first_payment_date }}</td>
                                  <td>{{ $loanDetail->last_payment_date }}</td>
                                  <td>{{ $loanDetail->loan_amount }}</td>
                              </tr>
                          @endforeach
                          @else
                          <tr>
                            <td colspan="6">No data found</td>
                        </tr>
                    @endif
                      </tbody>
                      </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
