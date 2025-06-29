@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Reporte</h2>
    <table class="table">
        <thead>
            <tr>
                @foreach($datos[0] as $key => $value)
                    <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $row)
                <tr>
                    @foreach($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
