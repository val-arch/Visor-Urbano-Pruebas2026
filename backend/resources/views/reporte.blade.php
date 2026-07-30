<style>
    body {
        font-family: Arial, sans-serif;
    }

    h1 {
        color: #333;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
    }

    table thead tr {
        background-color: #009879;
        color: white;
    }

    table thead th, table tbody td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    </style>
<div style="margin-left: 20px;margin-right: 20px">
<h1>Totales Licencias  y refrendos Emitidos por VUJ</h1>
<table>
    <thead>
        <tr>
            <th>Refrendos Emitidos por visor</th>
            <th>Licencias Nuevas Emitidas por visor</th>
            <th>Refrendos Historicos</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
            <tr>
                <td>{{ $total_emitidas_visor_refrendo }}</td>
                <td>{{ $total_emitidas_visor_nuevas }}</td>
                <td>{{ $total_emitidas_historico }}</td>
                <td>{{ $total_emitidas_visor_total + $total_emitidas_historico}}</td>
            </tr>

    </tbody>
</table>
<h1>Licencias y refrendos Emitidos por VUJ</h1>
<table>
    <thead>
        <tr>
            <th>Municipio ID</th>
            <th>Nombre</th>
            <th>Refrendos</th>
            <th>Nuevas</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $result)
            <tr>
                <td>{{ $result->id }}</td>
                <td>{{ $result->nombre }}</td>
                <td>{{ $result->total_refrendo }}</td>
                <td>{{ $result->total_nueva }}</td>
                <td>{{ $result->total_final }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<h1>Refrendos Subidos de historico</h1>
<table>
    <thead>
        <tr>
            <th>Municipio ID</th>
            <th>Nombre</th>
            <th>Conteo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results2 as $result)
            <tr>
                <td>{{ $result->id }}</td>
                <td>{{ $result->nombre }}</td>
                <td>{{ $result->count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
