@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h1>Emi Details</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-success" onclick="processEmiData()">Process Data</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body" id="emiDetailsContainer">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function processEmiData() {
        $.ajax({
            url: '{{ route('processEmiData') }}',
            type: 'GET',
            success: function(response) {
                var tableHTML = generateTableHTML(response);
                $('#emiDetailsContainer').html(tableHTML);
            },
            error: function(error) {
                console.error('Error processing EMI data:', error);
            }
        });
    }

    function generateTableHTML(data) {
        if (!data || data.length === 0) {
            return '<p>No data available.</p>';
        }

        var html = '<div class="table-responsive"><table class="table">';
        
        var columns = Object.keys(data[0]);
        
        html += '<thead><tr>';
        columns.forEach(function(column) {
            html += '<th>' + column + '</th>';
        });
        html += '</tr></thead>';
        
        html += '<tbody>';
        data.forEach(function(row) {
            html += '<tr>';
            columns.forEach(function(column) {
                html += '<td>' + row[column] + '</td>';
            });
            html += '</tr>';
        });
        html += '</tbody>';
        
        html += '</table></div>';
        return html;
    }
</script>
@endsection
