@extends('layouts.app')

@section('content')
<section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Profile Views</h6>
                                    <h6 class="font-extrabold mb-0">112.000</h6>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card"> 
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Followers</h6>
                                    <h6 class="font-extrabold mb-0">183.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Following</h6>
                                    <h6 class="font-extrabold mb-0">80.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Saved Post</h6>
                                    <h6 class="font-extrabold mb-0">112</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-profile-visit"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-primary" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Europe</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end">862</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-europe"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-success" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">America</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end">375</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-america"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-danger" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Indonesia</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end">1025</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-indonesia"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Latest Comments</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="./assets/compiled/jpg/5.jpg">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">Si Cantik</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">Congratulations on your graduation!</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="./assets/compiled/jpg/2.jpg">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">Si Ganteng</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">Wow amazing design! Can you make another tutorial for
                                                    this design?</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="./assets/compiled/jpg/1.jpg" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold">John Duck</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Recent Messages</h4>
                </div>
                <div class="card-content pb-4">
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="./assets/compiled/jpg/4.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Hank Schrader</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="./assets/compiled/jpg/5.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Dean Winchester</h5>
                            <h6 class="text-muted mb-0">@imdean</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="./assets/compiled/jpg/1.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">John Dodol</h5>
                            <h6 class="text-muted mb-0">@dodoljohn</h6>
                        </div>
                    </div>
                    <div class="px-4">
                        <button class='btn btn-block btn-xl btn-outline-primary font-bold mt-3'>Start Conversation</button>
                    </div>
                </div>
            </div> 
            <div class="card">
                <div class="card-header">
                    <h4>Visitors Profile</h4>
                </div>
                <div class="card-body">
                    <div id="chart-visitors-profile"></div>
                </div>
            </div>
        </div>


<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Absensi</h2>

    <!-- Filter Section -->
    <div class="bg-white p-4 rounded-lg shadow mb-6 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex flex-col">
            <label for="start-date" class="text-sm text-gray-700 mb-1">Tanggal Mulai</label>
            <input type="date" id="start-date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200" />
        </div>

        <div class="flex flex-col">
            <label for="end-date" class="text-sm text-gray-700 mb-1">Tanggal Sampai</label>
            <input type="date" id="end-date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200" />
        </div>

        <div class="flex flex-col">
            <button id="filter-btn" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-150 mt-1 md:mt-0">
                Terapkan
            </button>
        </div>

        <div class="flex flex-col">
            <a id="export-pdf" href="#" target="_blank"
               class="bg-green-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-700 transition duration-150 mt-1 md:mt-0 text-center">
                Export PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Map Display -->
        <div class="md:col-span-2">
            <h4 class="text-lg font-semibold text-gray-800 mb-3">Lokasi Absensi</h4>
            <div id="map" class="w-full h-[500px] rounded-lg shadow border"></div>
        </div>

        <!-- Sidebar Absensi -->
        <div class="space-y-3">
            <h4 class="text-lg font-semibold text-gray-800">Riwayat Absensi</h4>

            <input type="text" id="user-name" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm" placeholder="Cari nama..." />

            <div id="absensi-history" class="overflow-y-auto max-h-[500px] border rounded-lg bg-white shadow p-3 space-y-2 text-sm">
                <p class="text-gray-500 text-center">Silakan terapkan filter untuk memuat data.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('map').setView([-2.5, 118], 4);
    const layersGroup = {};
    let bounds = [];

    const blueIcon = new L.Icon({
        iconUrl: '{{ asset('icons/masuk.png') }}',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    const redIcon = new L.Icon({
        iconUrl: '{{ asset('icons/pulang.png') }}',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    document.getElementById('filter-btn').addEventListener('click', fetchAbsensiData);
    document.getElementById('user-name').addEventListener('input', fetchAbsensiData);

    function fetchAbsensiData() {
        const start = document.getElementById('start-date').value;
        const end = document.getElementById('end-date').value;
        const keyword = document.getElementById('user-name').value;

        const historyEl = document.getElementById('absensi-history');
        historyEl.innerHTML = "<p class='text-gray-500 text-center'>Memuat data...</p>";

        for (let key in layersGroup) {
            map.removeLayer(layersGroup[key]);
        }
        bounds = [];

        fetch(`/api/absensi-data?tanggal_mulai=${start}&tanggal_sampai=${end}&nama=${keyword}`)
            .then(res => res.json())
            .then(data => {
                historyEl.innerHTML = '';
                data.forEach(user => {
                    const userLayer = L.layerGroup();
                    let userHtml = '';

                    user.absens.forEach(absen => {
                        const masukLatLng = [absen.lat_masuk, absen.lon_masuk];
                        const pulangLatLng = [absen.lat_pulang, absen.lon_pulang];

                        if (absen.lat_masuk && absen.lon_masuk) {
                            L.marker(masukLatLng, { icon: blueIcon }).addTo(userLayer)
                                .bindPopup(`<b>${user.name}</b><br>Masuk: ${absen.jam_masuk}<br><a href="https://www.google.com/maps?q=${absen.lat_masuk},${absen.lon_masuk}" target="_blank" class="text-blue-600 underline">Lihat di Google Maps</a>`);
                            bounds.push(masukLatLng);
                        }

                        if (absen.lat_pulang && absen.lon_pulang) {
                            L.marker(pulangLatLng, { icon: redIcon }).addTo(userLayer)
                                .bindPopup(`<b>${user.name}</b><br>Pulang: ${absen.jam_pulang}<br><a href="https://www.google.com/maps?q=${absen.lat_pulang},${absen.lon_pulang}" target="_blank" class="text-blue-600 underline">Lihat di Google Maps</a>`);
                            bounds.push(pulangLatLng);
                        }

                        if (absen.lat_masuk && absen.lat_pulang) {
                            L.polyline([masukLatLng, pulangLatLng], {
                                color: 'purple',
                                weight: 2,
                                dashArray: '5, 5',
                                opacity: 0.7
                            }).addTo(userLayer);
                        }

                        userHtml += `
                            <div class='p-3 border rounded bg-gray-50 shadow-sm'>
                                <div class='font-semibold text-indigo-700'>${user.name}</div>
                                <div class='text-gray-700 text-sm'>${absen.tanggal}</div>
                                <div class='text-xs mt-1'>
                                    <b>Masuk:</b> ${absen.jam_masuk ?? '-'}<br>
                                    <b>Pulang:</b> ${absen.jam_pulang ?? '-'}
                                </div>
                                <div class='text-xs mt-1 text-gray-600'>${absen.kegiatan}</div>
                            </div>
                        `;
                    });

                    userLayer.addTo(map);
                    layersGroup[user.name] = userLayer;
                    historyEl.innerHTML += userHtml;
                });

                if (bounds.length > 0) {
                    map.fitBounds(bounds);
                }

                document.getElementById('export-pdf').href = `/export-pdf?tanggal_mulai=${start}&tanggal_sampai=${end}&nama=${keyword}`;
            });
    }
});
</script>
@endsection
