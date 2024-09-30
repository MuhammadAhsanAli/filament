<x-filament-panels::page class="rug-pattern">
    <!-- Background with Pattern -->
    <div class="relative">
        <!-- Export Buttons -->
        <div class="flex justify-end mx-auto button-section mb-4">
            <!-- Export to PDF Button -->
            <button class="text-white primary" wire:click="mountAction('pdfAction')">
                Export PDF
            </button>

            <!-- Export to Excel Button -->
            <button class="text-white primary" wire:click="mountAction('excelAction')">
                Export EXCEL
            </button>
        </div>

        <div class="flex justify-center items-center h-screen">
            <div class="relative bg-white p-8 shadow-lg rounded-lg">
                <!-- Dining Table (Extended Length) -->
                <div class="table-rectangle mx-auto relative">
                    <!-- Table label -->
                    <div class="absolute inset-0 flex justify-center items-center">
                        <span class="table-text">Dining</span>
                    </div>

                    <!-- Plates on the table -->
                    <div class="absolute inset-0 flex justify-between items-center">
                        <div class="plate"></div>
                        <div class="plate"></div>
                    </div>

                    <div class="absolute inset-0 flex justify-center items-between">
                        <div class="plate plate-gap"></div>
                        <div class="plate"></div>
                    </div>

                    <div class="absolute inset-0 flex justify-center items-end">
                        <div class="plate plate-gap"></div>
                        <div class="plate"></div>
                    </div>
                </div>

                <!-- Chairs around the Table with Seat Numbers -->
                @foreach($data as $seat)
                    <div class="chair-position chair-position-{{$seat['id']}}"
                         @if($seat['count'] < 1)
                         wire:click="mountAction('bookSeat', {'id': {{$seat['id']}} })"
                        @endif
                    >
                        <div class="seat-wrapper">
                            <img class="chair-img-{{$seat['id']}}" src="{{ asset('images/pages/chair.png') }}" alt="Chair Image for Seat {{$seat['id']}}"/>
                            @if($seat['count'] > 0)
                                <span class="seat-number"> {{$seat['name']}}</span>
                            @else
                                <span class="seat-number border-2">{{$seat['id']}}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Modal Definition -->
</x-filament-panels::page>
