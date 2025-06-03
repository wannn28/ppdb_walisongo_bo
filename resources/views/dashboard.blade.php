@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card Users -->
        <div class="bg-blue-400 text-white rounded-lg p-6 shadow-md">
            <div class="text-3xl font-bold">120</div>
            <div class="text-lg">Users</div>
        </div>
        
        <!-- Card Peserta -->
        <div class="bg-green-500 text-white rounded-lg p-6 shadow-md">
            <div class="text-3xl font-bold">120</div>
            <div class="text-lg">Peserta</div>
        </div>
        
        <!-- Card Siswa -->
        <div class="bg-purple-500 text-white rounded-lg p-6 shadow-md">
            <div class="text-3xl font-bold">50</div>
            <div class="text-lg">Siswa</div>
        </div>
        
        <!-- Card Transaksi -->
        <div class="bg-amber-400 text-white rounded-lg p-6 shadow-md">
            <div class="text-3xl font-bold">60</div>
            <div class="text-lg">Transaksi</div>
        </div>
        
        <!-- Card Transaksi (Pink) -->
        <div class="bg-pink-400 text-white rounded-lg p-6 shadow-md">
            <div class="text-3xl font-bold">70</div>
            <div class="text-lg">transaksi</div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Chart Siswa -->
        <div class="bg-white rounded-lg p-6 shadow-md">
            <h2 class="text-xl font-semibold mb-4">Chart Siswa</h2>
            <div class="h-64 flex items-end space-x-4">
                {{-- <div class="flex-1 flex flex-col items-center">
                    <div class="bg-blue-400 w-full h-32 rounded-t-md"></div>
                    <div class="mt-2">SD</div>
                </div> --}}
                {{-- <div class="flex-1 flex flex-col items-center">
                    <div class="bg-green-500 w-full h-48 rounded-t-md"></div>
                    <div class="mt-2">SMP 1</div>
                </div> --}}
                <div class="flex-1 flex flex-col items-center">
                    <div class="bg-green-500 w-full h-48 rounded-t-md"></div>
                    <div class="mt-2">SMP</div>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="bg-purple-500 w-full h-36 rounded-t-md"></div>
                    <div class="mt-2">SMA</div>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="bg-amber-400 w-full h-40 rounded-t-md"></div>
                    <div class="mt-2">SMK</div>
                </div>
            </div>
        </div>
        
        <!-- Chart Perbandingan -->
        <div class="bg-white rounded-lg p-6 shadow-md">
            <h2 class="text-xl font-semibold mb-4">Chart Perbandingan Siswa & Peserta</h2>
            <div class="h-64 flex justify-center items-center">
                <!-- Pie Chart (Placeholder) -->
                <div class="w-48 h-48 rounded-full border-8 border-cyan-400 relative">
                    <div class="absolute inset-0 border-t-8 border-r-8 border-cyan-200 rounded-full" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 0 0);"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                        <div class="text-xs text-gray-500">Siswa: 25%</div>
                        <div class="text-xs text-gray-500">Peserta: 75%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection