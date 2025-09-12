<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-black-200 mb-6">Medību kalendārs Latvijā</h1>

        @php
            $months = range(1, 12);
            $monthNames = ['Janvāris','Februāris','Marts','Aprīlis','Maijs','Jūnijs','Jūlijs','Augusts','Septembris','Oktobris','Novembris','Decembris'];

            $animals = [
                ['name'=>'Sarkanais briedis','type'=>'brieži','months'=>[8,9,10,11,12,1]],
                ['name'=>'Staltbrieži','type'=>'brieži','months'=>[6,7,8,9,10,11]],
                ['name'=>'Alnis','type'=>'brieži','months'=>[9,10,11,12]],
                ['name'=>'Mežacūka','type'=>'brieži','months'=>range(1,12)],
                ['name'=>'Lūsis','type'=>'plēsēji','months'=>[]], // tikai ar atļaujām
                ['name'=>'Brūnais lācis','type'=>'plēsēji','months'=>[8,9,10]],
                ['name'=>'Ūdensputni','type'=>'putni','months'=>[8,9,10,11,12,1]],
            ];

            $colors = [
                'brieži' => 'bg-emerald-500',
                'plēsēji' => 'bg-yellow-600',
                'putni' => 'bg-blue-500',
            ];
        @endphp

        <div class="flex space-x-6 overflow-x-auto pb-6">
            @foreach($months as $index => $month)
                @php
                    $year = date('Y');
                    $firstDay = date('w', strtotime("$year-$month-01")); // 0 = Sunday
                    $daysInMonth = date('t', strtotime("$year-$month-01"));
                @endphp

                <div class="flex-none w-96 bg-gray-800 rounded-lg shadow-lg p-4 text-gray-200">
                    <h2 class="text-xl font-semibold mb-4 text-center">{{ $monthNames[$index] }} {{ $year }}</h2>
                    
                    {{-- Weekday headers --}}
                    <div class="grid grid-cols-7 text-center font-semibold text-gray-400 mb-2">
                        <div>P</div><div>O</div><div>T</div><div>C</div><div>P</div><div>S</div><div>S</div>
                    </div>

                    {{-- Calendar days --}}
                    <div class="grid grid-cols-7 gap-1">
                        @for($i = 0; $i < ($firstDay == 0 ? 6 : $firstDay-1); $i++)
                            <div></div>
                        @endfor

                        @for($day = 1; $day <= $daysInMonth; $day++)
                            <div class="h-12 flex items-center justify-center bg-gray-700 rounded text-gray-200 font-semibold">{{ $day }}</div>
                        @endfor
                    </div>

                    {{-- Animals for this month --}}
                    <div class="mt-4">
                        <h3 class="font-semibold mb-2 text-lg">Medību dzīvnieki:</h3>
                        <div class="flex flex-wrap gap-2">
                            @php $hasAnimals = false; @endphp
                            @foreach($animals as $animal)
                                @if(in_array($month, $animal['months']))
                                    @php $hasAnimals = true; @endphp
                                    <span class="px-2 py-1 text-sm {{ $colors[$animal['type']] }} rounded text-white">{{ $animal['name'] }}</span>
                                @endif
                            @endforeach
                            @if(!$hasAnimals)
                                <p class="text-gray-400 italic text-sm">Nav medību šajā mēnesī</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="mt-4 text-gray-400 text-sm">
            Lūsis – medības tikai ar atļaujām.
        </p>
    </div>
</x-app-layout>
