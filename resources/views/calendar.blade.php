<x-app-layout>
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

    <div 
        x-data="{ monthIndex: 0 }" 
        class="relative w-full h-screen bg-gray-900 text-gray-200 flex flex-col items-center justify-center overflow-hidden"
    >
        {{-- Month sections --}}
        @foreach($months as $index => $month)
            @php
                $year = date('Y');
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            @endphp

            <section
                x-show="monthIndex === {{ $index }}"
                x-transition
                class="absolute inset-0 flex flex-col items-center justify-start p-8"
            >
                <h2 class="text-4xl font-bold mb-6">
                    {{ $monthNames[$index] }} {{ $year }}
                </h2>

                {{-- Calendar --}}
                <div class="w-full max-w-4xl">
                    {{-- Week headers --}}
                    <div class="grid grid-cols-7 gap-2 text-center font-semibold text-gray-400 mb-2">
                        <div>P</div><div>O</div><div>T</div><div>C</div><div>P</div><div>S</div><div>S</div>
                    </div>

                    {{-- Days grid --}}
                    <div class="grid grid-cols-7 gap-2">
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            <div class="h-20 flex items-center justify-center bg-gray-700 rounded font-semibold">
                                {{ $day }}
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Animals --}}
                <div class="mt-6 w-full max-w-4xl">
                    <h3 class="font-semibold mb-2 text-lg">Medību dzīvnieki:</h3>
                    <div class="flex flex-wrap gap-2">
                        @php $hasAnimals = false; @endphp
                        @foreach($animals as $animal)
                            @if(in_array($month, $animal['months']))
                                @php $hasAnimals = true; @endphp
                                <span class="px-3 py-1 text-sm {{ $colors[$animal['type']] }} rounded text-white shadow">
                                    {{ $animal['name'] }}
                                </span>
                            @endif
                        @endforeach
                        @unless($hasAnimals)
                            <p class="text-gray-400 italic text-sm">Nav medību šajā mēnesī</p>
                        @endunless
                    </div>
                </div>
            </section>
        @endforeach

        {{-- Controls --}}
        <button
            @click="monthIndex = (monthIndex - 1 + 12) % 12"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-full shadow"
        >
            ‹
        </button>
        <button
            @click="monthIndex = (monthIndex + 1) % 12"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-full shadow"
        >
            ›
        </button>

        {{-- Footer note --}}
        <p class="absolute bottom-4 text-gray-400 text-sm bg-gray-800 px-4 py-2 rounded">
            Lūsis – medības tikai ar atļaujām.
        </p>
    </div>
</x-app-layout>
