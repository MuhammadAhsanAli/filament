<?php

namespace App\Filament\Pages;

use App\Exports\OrdersExport;
use App\Models\Dish;
use App\Models\DishDetail;
use App\Models\Drink;
use App\Models\DrinkDetail;
use App\Models\Order;
use App\Models\SeatDetail;
use App\Models\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Blade;
use Maatwebsite\Excel\Facades\Excel;
use Exception as BaseException;

class Dashboard extends Page
{
    use InteractsWithActions, InteractsWithForms, WithRateLimiting;

    /** @var array */
    public array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.dashboard';
    protected ?string $heading = '';

    /**
     * Mount the dashboard page and load initial data.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->loadTableData();
    }

    /**
     * Load seat data related to the table.
     *
     * @return void
     */
    protected function loadTableData(): void
    {
        $tableData = Table::with(['seat.seatDetails.order'])->find(1);
        if ($tableData) {
            $this->data = $tableData->seat->map(fn($seat) => [
                'id' => $seat->id,
                'name' => $seat->seatDetails->first()->order->name ?? null,
                'count' => $seat->seatDetails->count(),
            ])->toArray();
        }
    }

    /**
     * Define the action to book a seat.
     *
     * @return Action
     */
    public function bookSeat(): Action
    {
        return Action::make('bookSeat')
            ->label('Seat ' . $this->mountedActionsArguments[0]['id'])
            ->modalSubmitActionLabel('Update')
            ->color('info')
            ->modalCancelAction(false)
            ->modalFooterActionsAlignment('right')
            ->stickyModalHeader(true)
            ->stickyModalFooter(true)
            ->form($this->getBookingFormSchema())
            ->action(fn(array $data) => $this->handleBooking($data))
            ->modalWidth('md');
    }

    /**
     * Define the schema for the booking form.
     *
     * @return array
     */
    protected function getBookingFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Name')
                ->prefixIcon('heroicon-s-user')
                ->placeholder('Name')
                ->required(),
            TextInput::make('email')
                ->label('Email')
                ->placeholder('Email')
                ->required(),
            Select::make('Dish')
                ->placeholder('Dish')
                ->options(Dish::all()->pluck('meal', 'id'))
                ->native(false),
            Select::make('Drink')
                ->placeholder('Drink')
                ->options(Drink::all()->pluck('name', 'id'))
                ->native(false),
            Textarea::make('Comment')
                ->placeholder('Comment')
                ->required()
        ];
    }

    /**
     * Handle the seat booking logic.
     *
     * @param array $data
     * @return void
     */
    protected function handleBooking(array $data): void
    {
        try {
            $order = Order::create($this->prepareOrderData($data));

            SeatDetail::create([
                'order_id' => $order->id,
                'seat_id' => $this->mountedActionsArguments[0]['id'],
            ]);

            $this->createDishDetail($data['Dish'] ?? null, $order->id);
            $this->createDrinkDetail($data['Drink'] ?? null, $order->id);

            $this->sendNotification('Success!', "Seat booked successfully for {$data['name']}.");
        } catch (BaseException $e) {
            $this->sendNotification('Warning!', "Seat has not been booked for {$data['name']}. Error: {$e->getMessage()}", true);
        }
    }

    /**
     * Prepare order data for creation.
     *
     * @param array $data
     * @return array
     */
    private function prepareOrderData(array $data): array
    {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'comments' => $data['Comment'],
        ];
    }

    /**
     * Create dish detail if a dish is selected.
     *
     * @param int|null $dishId
     * @param int $orderId
     * @return void
     */
    private function createDishDetail(?int $dishId, int $orderId): void
    {
        if ($dishId) {
            DishDetail::create([
                'order_id' => $orderId,
                'dish_id' => $dishId,
            ]);
        }
    }

    /**
     * Create drink detail if a drink is selected.
     *
     * @param int|null $drinkId
     * @param int $orderId
     * @return void
     */
    private function createDrinkDetail(?int $drinkId, int $orderId): void
    {
        if ($drinkId) {
            DrinkDetail::create([
                'order_id' => $orderId,
                'drink_id' => $drinkId,
            ]);
        }
    }

    /**
     * Send notification to the user.
     *
     * @param string $title
     * @param string $message
     * @param bool $isError
     * @return void
     */
    private function sendNotification(string $title, string $message, bool $isError = false): void
    {
        Notification::make()
            ->title($title)
            ->body($message)
            ->{$isError ? 'danger' : 'success'}()
            ->send();
    }

    /**
     * Action to generate a PDF.
     *
     * @return Action
     */
    public function pdfAction(): Action
    {
        return Action::make('PDF')
            ->action(fn () => $this->streamPdf());
    }

    /**
     * Stream the generated PDF.
     *
     * @return \Illuminate\Http\Response
     */
    private function streamPdf()
    {
        return response()->streamDownload(function () {
            echo Pdf::loadHtml(
                Blade::render('filament.pages.pdf', ['records' => $this->getData()])
            )->stream();
        }, $this->getFileName() . '.pdf');
    }

    /**
     * Action to export data to Excel.
     *
     * @return Action
     */
    public function excelAction(): Action
    {
        return Action::make('Export')
            ->action(fn () => Excel::download(new OrdersExport($this->getData()), $this->getFileName() . '.csv'));
    }

    /**
     * Retrieve relevant data for the dashboard.
     *
     * @return Table|null
     */
    protected function getData(): ?Table
    {
        return Table::with([
            'seat.seatDetails.order',
            'seat.seatDetails.order.drinkDetails.drink',
            'seat.seatDetails.order.dishDetails.dish'
        ])->find(1);
    }

    /**
     * Generate a unique file name for exports.
     *
     * @return string
     */
    protected function getFileName(): string
    {
        return rand(1, 100) . "-" . date('Y-m-d');
    }
}
