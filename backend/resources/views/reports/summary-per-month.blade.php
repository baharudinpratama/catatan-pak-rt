<!DOCTYPE html>
<html>

<head>
    <title>Laravel PDF</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        body {
            margin-right: 0.8cm;
        }
    </style>
</head>

<body>
    <h1 class="text-center">{{ $title }}</h1>
    <h3 class="text-center">{{ $subtitle }}</h3>

    <div class="row">
        <div class="col-12 mt-5">
            <table class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th class="text-center">Bulan</th>
                        <th class="text-center">Pendapatan</th>
                        <th class="text-center">Pengeluaran</th>
                        <th class="text-center">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @if (sizeof($summaryPerMonths) <= 0)
                        <tr>
                            <td class="text-center" colspan="4">
                                Tidak ditemukan data.
                            </td>
                        </tr>
                    @else
                        @foreach ($summaryPerMonths as $summaryPerMonth)
                            <tr>
                                <td width="25%">{{ $summaryPerMonth['monthName'] }}</td>
                                <td class="text-right" width="25%">{{ $summaryPerMonth['income'] }}</td>
                                <td class="text-right" width="25%">{{ $summaryPerMonth['expense'] }}</td>
                                <td class="text-right" width="25%">{{ $summaryPerMonth['balance'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="font-weight-bold" colspan="3">Total</td>
                            <td class="font-weight-bold text-right">{{ $balanceTotal }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
