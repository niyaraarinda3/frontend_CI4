<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        td{
           text-align: center;
        }
    </style>
</head>
<body class="p-6">
    <div class="flex mb-4">
        <div class="w-1/4 mr-2">
            <label for="area" class="block font-bold mb-1">Select Area:</label>
            <select id="area" class="w-full p-2 border rounded">
                <option value="">===== Pilih ====</option>
            </select>
        </div>

        <div class="w-1/4 mx-2">
            <label for="dateFrom" class="block font-bold mb-1">Select dateFrom:</label>
            <input type="date" id="dateFrom" class="w-full p-2 border rounded">
        </div>

        <div class="w-1/4 ml-2">
            <label for="dateTo" class="block font-bold mb-1">Select dateTo:</label>
            <input type="date" id="dateTo" class="w-full p-2 border rounded">
        </div>
    
        <div class="w-1/4 ml-7">
    <button id="viewData" class="w-1/3 mt-7 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        View
    </button>
    </div>
    </div>
    <div id="chartContainer" class="mt-8">
        <canvas id="myChart" style="height: 300px;"></canvas>
    </div>

    <table id="dataTable" class="mt-4 w-full table table-bordered text-center">
        <thead>
            <tr id="dataTableHead">
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    
    <script src="<?= base_url('js/scripts.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function generateData() {
            var tableBody = document.querySelector('#dataTable tbody');
            var myChart = document.getElementById('myChart');
            var chartContainer = document.getElementById('chartContainer');
            var area = document.getElementById('area');
            tableBody.innerHTML = '';
            myChart.remove();
            chartContainer.innerHTML = `<canvas id="myChart" style="height: 300px;"></canvas>`;


            var selectedArea = document.getElementById('area').value;
            var dateFrom = document.getElementById('dateFrom').value;
            var dateTo = document.getElementById('dateTo').value;
            $.ajax({
                url: "http://localhost:3000/pages/",
                data:{
                    selectedArea: selectedArea,
                    dateFrom: dateFrom,
                    dateTo: dateTo
                },
                type: "GET",
                dataType: "json",
                success: function (res) {
                console.log(res);
                document.getElementById('dataTableHead').innerHTML = `<th class="py-2">Brand</th>`;
                area.innerHTML = `<option value="">===== Pilih ====</option>`;
                res.data.allArea.forEach((item) => {
                    option = document.createElement('option');
                    option.innerHTML = `<option value="${item}">${item}</option>`;
                    area.appendChild(option);
                })
                res.data.listArea.forEach((item) => {
                    var row = document.createElement('th');
                    row.innerHTML = `<th class="py-2">${item}</th>`;
                    document.getElementById('dataTableHead').appendChild(row);
                })
                    Object.keys(res.data.dataProduct).forEach(function(key) {
                        var row = document.createElement('tr');
                        html =  `<td>${key}</td>`;
                        res.data.listArea.forEach((item) => {
                            html += `<td>${res.data.dataProduct[key][item]}%</td>`;
                            console.log(key,item,res.data.dataProduct[key][item])
                        })
                        row.innerHTML = html;
                        tableBody.appendChild(row);
                });

            var labels = []
            var values = []
            let n = 0;
            Object.keys(res.data.dataChart).forEach(function(key) {
                console.log(key, res.data.dataChart[key]);
                labels[n] = key;
                values[n] = res.data.dataChart[key];
                n++;
            });
            console.log(labels,values);

            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Value',
                        data: values,
                        backgroundColor: 'rgb(85, 148, 243)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
                   
            },
            

            })
            .fail(function (jqXHR, textStatus, errorThrown) { 
                alert("Data Tidak Ditemukan");
            })
                    
        };

        document.getElementById('viewData').addEventListener('click', function() {
            generateData();
        });

        generateData();

    </script>
</body>
</html>
