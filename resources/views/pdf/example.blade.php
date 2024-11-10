<h1 class="font-2xl">Timesheets</h1>
<table>
    <thead>
        <th>Calendario</th>
        <th>Tipo</th>
        <th>Entrada</th>
        <th>Salida</th>
    </thead>
    <tbody>
        @foreach ($timesheets as $timesheet)
        <tr>
            <td>{{ $timesheet->calendar->name }}</td>
            <td> {{ $timesheet->type }}</td>
            <td> {{ $timesheet->day_in }}</td>
            <td> {{ $timesheet->day_out }}</td>
        </tr>
        @endforeach
    </tbody>
</table>