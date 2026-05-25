<?php

namespace App\Livewire;

use App\Enums\RentalStatus;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\Person;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class BookingWizard extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    use WithFileUploads;

    public int $vehicleId;

    public ?array $data = [];

    public function mount(int $vehicleId): void
    {
        $this->vehicleId = $vehicleId;

        $vehicle = $this->getVehicle();
        $defaultDriverOption = $vehicle->requires_driver ? 'with-driver' : 'self-drive';

        $this->form->fill([
            'start_date' => now()->addHour()->format('Y-m-d\TH:i'),
            'is_half_day' => false,
            'full_day_duration' => 1,
            'driver_option' => $defaultDriverOption,
            'delivery_method' => 'pickup',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $banks = Bank::all();

        return $schema
            ->components([
                Wizard::make([
                    Step::make(__('Date & Duration'))
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Section::make(__('Vehicle'))
                                ->schema([
                                    Placeholder::make('vehicle_info')
                                        ->hiddenLabel()
                                        ->content(function () {
                                            $vehicle = $this->getVehicle();
                                            $rate = number_format($vehicle->rental_rate_per_day ?? 0, 0, ',', '.');
                                            $rateBlock = number_format((int) ceil(($vehicle->rental_rate_per_day ?? 0) / 2), 0, ',', '.');

                                            return new HtmlString("
                                                <div class='flex items-center gap-4'>
                                                    <div class='text-sm'>
                                                        <p class='font-semibold text-base'>{$vehicle->name}</p>
                                                        <p class='text-gray-500'>{$vehicle->year} &middot; ".__($vehicle->transmission)."</p>
                                                        <p class='text-accent-600 font-medium'>Rp {$rate} ".__('/day')."</p>
                                                        <p class='text-xs text-gray-400'>Rp {$rateBlock} ".__('/ 12h block').'</p>
                                                    </div>
                                                </div>
                                            ');
                                        }),
                                ])
                                ->collapsed(false),
                            DateTimePicker::make('start_date')
                                ->label(__('Pickup Date & Time'))
                                ->required()
                                ->native(false)
                                ->minDate(now())
                                ->live()
                                ->seconds(false)
                                ->displayFormat('d M Y, H:i'),
                            Toggle::make('is_half_day')
                                ->label(__('Half Day (12 hours)'))
                                ->live(),
                            Select::make('full_day_duration')
                                ->label(__('Duration'))
                                ->options([
                                    1 => __('1 day (24h)'),
                                    2 => __('2 days (48h)'),
                                    3 => __('3 days (72h)'),
                                    4 => __('4 days (96h)'),
                                    5 => __('5 days (120h)'),
                                    6 => __('6 days (144h)'),
                                    7 => __('7 days (168h)'),
                                ])
                                ->visible(fn (Get $get): bool => ! $get('is_half_day'))
                                ->required()
                                ->live(),
                        ]),
                    Step::make(__('Vehicle Options'))
                        ->icon('heroicon-o-truck')
                        ->schema([
                            Radio::make('driver_option')
                                ->label(__('Driver Option'))
                                ->options(function () {
                                    $requiresDriver = $this->getVehicle()->requires_driver;
                                    if ($requiresDriver) {
                                        return ['with-driver' => __('With Driver')];
                                    }

                                    return [
                                        'self-drive' => __('Self Drive'),
                                        'with-driver' => __('With Driver'),
                                    ];
                                })
                                ->descriptions(function () {
                                    $vehicle = $this->getVehicle();
                                    $driver = Person::where('type', 'driver')->first();
                                    $feeFmt = $driver
                                        ? number_format($driver->driver_fee_per_day, 0, ',', '.')
                                        : null;

                                    $selfDriveDesc = __('You drive the vehicle yourself');
                                    $withDriverDesc = $feeFmt
                                        ? __('Professional driver — estimated Rp :fee/day', ['fee' => $feeFmt])
                                        : __('Professional driver included');

                                    $requiresDriver = $vehicle->requires_driver;
                                    if ($requiresDriver) {
                                        return ['with-driver' => $withDriverDesc];
                                    }

                                    return [
                                        'self-drive' => $selfDriveDesc,
                                        'with-driver' => $withDriverDesc,
                                    ];
                                })
                                ->required()
                                ->live(),
                            Select::make('driver_id')
                                ->label(__('Select Driver'))
                                ->options(function () {
                                    return Person::query()
                                        ->where('type', 'driver')
                                        ->with('user')
                                        ->get()
                                        ->mapWithKeys(fn (Person $driver) => [
                                            $driver->id => $driver->user->name.' - '.$driver->phone,
                                        ]);
                                })
                                ->searchable()
                                ->visible(function (Get $get): bool {
                                    $vehicle = $this->getVehicle();
                                    if ($vehicle->requires_driver) {
                                        return true;
                                    }

                                    return $get('driver_option') === 'with-driver';
                                })
                                ->required(function (Get $get): bool {
                                    $vehicle = $this->getVehicle();
                                    if ($vehicle->requires_driver) {
                                        return true;
                                    }

                                    return $get('driver_option') === 'with-driver';
                                })
                                ->live(),
                            Radio::make('delivery_method')
                                ->label(__('Delivery Method'))
                                ->options([
                                    'pickup' => __('Pickup'),
                                    'delivery' => __('Delivery'),
                                ])
                                ->descriptions(function () {
                                    return [
                                        'pickup' => __('Pick up at our office (Jl. Merdeka No. 123, Jakarta)'),
                                        'delivery' => __('We deliver the vehicle to your location'),
                                    ];
                                })
                                ->required()
                                ->live(),
                            Textarea::make('delivery_address')
                                ->label(__('Delivery Address'))
                                ->placeholder(__('Enter your delivery address'))
                                ->visible(fn (Get $get): bool => $get('delivery_method') === 'delivery')
                                ->required(fn (Get $get): bool => $get('delivery_method') === 'delivery'),
                        ]),
                    Step::make(__('Your Info'))
                        ->icon('heroicon-o-user')
                        ->visible(fn () => ! Auth::check())
                        ->schema([
                            Placeholder::make('login_prompt')
                                ->hiddenLabel()
                                ->content(new HtmlString(
                                    '<div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-gray-600">'
                                    .__('Already have an account?')
                                    .' <a href="/dashboard/login" class="text-accent-600 font-medium hover:underline">'
                                    .__('Log in')
                                    .'</a></div>'
                                )),
                            TextInput::make('customer_name')
                                ->label(__('Full Name'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder(__('Enter your full name')),
                            TextInput::make('customer_email')
                                ->label(__('Email'))
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique('users', 'email', ignorable: null)
                                ->placeholder(__('email@example.com')),
                            TextInput::make('customer_password')
                                ->label(__('Password'))
                                ->password()
                                ->required()
                                ->minLength(8)
                                ->placeholder(__('Min. 8 characters')),
                            TextInput::make('customer_password_confirmation')
                                ->label(__('Confirm Password'))
                                ->password()
                                ->required()
                                ->same('customer_password')
                                ->placeholder(__('Re-enter your password')),
                            TextInput::make('customer_phone')
                                ->label(__('Phone Number'))
                                ->tel()
                                ->required()
                                ->maxLength(20)
                                ->placeholder(__('08xxxxxxxxxx')),
                        ])
                        ->columns(2),
                    Step::make(__('Identity & Address'))
                        ->icon('heroicon-o-identification')
                        ->visible(fn () => ! Auth::check())
                        ->schema([
                            Textarea::make('customer_address')
                                ->label(__('Address'))
                                ->required()
                                ->maxLength(500)
                                ->placeholder(__('Enter your address'))
                                ->rows(2),
                            Select::make('customer_id_type')
                                ->label(__('ID Type'))
                                ->options([
                                    'ktp' => __('KTP'),
                                    'sim' => __('SIM'),
                                    'paspor' => __('Paspor'),
                                    'kartu pelajar' => __('Kartu Pelajar'),
                                ])
                                ->required(),
                            FileUpload::make('id_file')
                                ->label(__('ID Document'))
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                                ->maxSize(2048)
                                ->directory('id-documents')
                                ->hint(__('JPG, PNG, or PDF. Max 2MB.'))
                                ->nullable(),
                        ]),
                    Step::make(__('Review & Payment'))
                        ->icon('heroicon-o-credit-card')
                        ->schema([
                            Section::make(__('Booking Summary'))
                                ->schema([
                                    Placeholder::make('booking_summary')
                                        ->hiddenLabel()
                                        ->content(function () {
                                            if (! $this->startDateTime) {
                                                return new HtmlString('<p class="text-sm text-gray-400">'.__('No booking details available.').'</p>');
                                            }

                                            $vehicle = $this->getVehicle();
                                            $subtotalFmt = number_format($this->subtotal, 0, ',', '.');
                                            $driverFeeFmt = number_format($this->driverFee, 0, ',', '.');
                                            $totalFmt = number_format($this->totalAmount, 0, ',', '.');

                                            $driverLine = '';
                                            if ($this->driverFee > 0) {
                                                $driverName = $this->driverOption === 'with-driver' && $this->driverId
                                                    ? Person::find($this->driverId)?->user?->name ?? __('With Driver')
                                                    : __('Self Drive');
                                                $driverLine = "
                                                    <div class='flex justify-between'>
                                                        <span class='text-gray-600'>".__('Driver')."</span>
                                                        <span>{$driverName}</span>
                                                    </div>
                                                    <div class='flex justify-between'>
                                                        <span class='text-gray-600'>".__('Driver fee')."</span>
                                                        <span>Rp {$driverFeeFmt}</span>
                                                    </div>";
                                            }

                                            $deliveryLine = '';
                                            if (($this->data['delivery_method'] ?? '') === 'delivery') {
                                                $deliveryLine = "
                                                    <div class='flex justify-between'>
                                                        <span class='text-gray-600'>".__('Delivery').'</span>
                                                        <span>'.__('Yes').'</span>
                                                    </div>';
                                            }

                                            return new HtmlString("
                                                <div class='space-y-2 text-sm'>
                                                    <h3 class='font-semibold text-gray-900'>{$vehicle->name}</h3>
                                                    <div class='flex justify-between'>
                                                        <span class='text-gray-600'>".__('Pickup:')."</span>
                                                        <span>{$this->startDateTime->format('d M Y, H:i')}</span>
                                                    </div>
                                                    <div class='flex justify-between'>
                                                        <span class='text-gray-600'>".__('Return:')."</span>
                                                        <span>{$this->endDateTime->format('d M Y, H:i')}</span>
                                                    </div>
                                                    <div class='flex justify-between'>
                                                        <span class='text-gray-600'>{$vehicle->name} &times; {$this->totalBlocks} ".__('block(s)')."</span>
                                                        <span>Rp {$subtotalFmt}</span>
                                                    </div>
                                                    {$driverLine}
                                                    {$deliveryLine}
                                                    <div class='flex justify-between font-bold text-lg pt-2 border-t'>
                                                        <span>".__('Total')."</span>
                                                        <span class='text-accent-700'>Rp {$totalFmt}</span>
                                                    </div>
                                                </div>
                                            ");
                                        }),
                                ]),
                            Radio::make('payment_method')
                                ->label(__('Payment Method'))
                                ->options([
                                    'cash' => __('Cash'),
                                    'transfer' => __('Bank Transfer'),
                                ])
                                ->required()
                                ->live(),
                            Select::make('bank_id')
                                ->label(__('Bank'))
                                ->options(function () use ($banks) {
                                    return $banks->mapWithKeys(fn (Bank $bank) => [
                                        $bank->id => "{$bank->name} ({$bank->code}) — {$bank->number} ({$bank->account_holder})",
                                    ]);
                                })
                                ->searchable()
                                ->visible(fn (Get $get): bool => $get('payment_method') === 'transfer')
                                ->required(fn (Get $get): bool => $get('payment_method') === 'transfer'),
                            FileUpload::make('payment_proof')
                                ->label(__('Upload Payment Proof'))
                                ->image()
                                ->directory('payment-proofs')
                                ->visible(fn (Get $get): bool => $get('payment_method') === 'transfer')
                                ->nullable()
                                ->helperText(__('Upload your transfer receipt/screenshot. You can also do this later from your dashboard.')),
                        ]),
                ])
                    ->nextAction(fn (Action $action) => $action->label(__('Next'))->color('primary'))
                    ->previousAction(fn (Action $action) => $action->label(__('Back'))->color('gray'))
                    ->submitAction(new HtmlString(
                        '<button type="button" wire:click="submit" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-700 fi-text-color-950 hover:fi-text-color-800 dark:fi-text-color-0 dark:hover:fi-text-color-0 fi-btn fi-size-md fi-ac-btn-action">'.__('Submit Booking').'</button>'
                    )),
            ])
            ->statePath('data');
    }

    // -------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------

    protected function getVehicle(): Vehicle
    {
        return Vehicle::findOrFail($this->vehicleId);
    }

    // -------------------------------------------------------------------
    // Computed properties (read from form $data)
    // -------------------------------------------------------------------

    public function getVehicleProperty(): Vehicle
    {
        return $this->getVehicle();
    }

    public function getDriversProperty()
    {
        return Person::query()->where('type', 'driver')->with('user')->get();
    }

    public function getStartDateProperty(): ?string
    {
        return $this->data['start_date'] ?? null;
    }

    public function getIsHalfDayProperty(): bool
    {
        return $this->data['is_half_day'] ?? false;
    }

    public function getFullDayDurationProperty(): int
    {
        return (int) ($this->data['full_day_duration'] ?? 1);
    }

    public function getDriverOptionProperty(): string
    {
        return $this->data['driver_option'] ?? 'self-drive';
    }

    public function getDriverIdProperty(): ?int
    {
        return isset($this->data['driver_id']) ? (int) $this->data['driver_id'] : null;
    }

    public function getCustomerNameProperty(): string
    {
        return $this->data['customer_name'] ?? '';
    }

    public function getCustomerEmailProperty(): string
    {
        return $this->data['customer_email'] ?? '';
    }

    public function getCustomerPasswordProperty(): string
    {
        return $this->data['customer_password'] ?? '';
    }

    public function getCustomerPhoneProperty(): string
    {
        return $this->data['customer_phone'] ?? '';
    }

    public function getCustomerAddressProperty(): string
    {
        return $this->data['customer_address'] ?? '';
    }

    public function getCustomerIdTypeProperty(): string
    {
        return $this->data['customer_id_type'] ?? '';
    }

    public function getPaymentMethodProperty(): string
    {
        return $this->data['payment_method'] ?? '';
    }

    public function getStartDateTimeProperty(): ?Carbon
    {
        $startDate = $this->data['start_date'] ?? null;
        if (! $startDate) {
            return null;
        }

        return Carbon::parse($startDate);
    }

    public function getEndDateTimeProperty(): ?Carbon
    {
        if (! $this->startDateTime) {
            return null;
        }

        $isHalfDay = $this->data['is_half_day'] ?? false;
        $hours = $isHalfDay ? 12 : (((int) ($this->data['full_day_duration'] ?? 1)) * 24);

        return $this->startDateTime->copy()->addHours($hours);
    }

    public function getTotalHoursProperty(): int
    {
        if (! $this->startDateTime || ! $this->endDateTime) {
            return 0;
        }

        return (int) abs($this->endDateTime->diffInHours($this->startDateTime));
    }

    public function getTotalBlocksProperty(): int
    {
        if ($this->totalHours === 0) {
            return 0;
        }

        return (int) ceil($this->totalHours / 12);
    }

    public function getRatePerBlockProperty(): int
    {
        return (int) ceil(($this->getVehicle()->rental_rate_per_day ?? 0) / 2);
    }

    public function getSubtotalProperty(): int
    {
        return $this->totalBlocks * $this->ratePerBlock;
    }

    public function getDriverFeeProperty(): int
    {
        $driverOption = $this->data['driver_option'] ?? '';
        $driverId = isset($this->data['driver_id']) ? (int) $this->data['driver_id'] : null;

        if ($driverOption !== 'with-driver' || ! $driverId) {
            return 0;
        }

        $driver = Person::find($driverId);
        $feePerDay = $driver->driver_fee_per_day ?? 0;
        $feePerBlock = (int) ceil($feePerDay / 2);

        return $this->totalBlocks * $feePerBlock;
    }

    public function getTotalAmountProperty(): int
    {
        return $this->subtotal + $this->driverFee;
    }

    // -------------------------------------------------------------------
    // Actions
    // -------------------------------------------------------------------

    public function submit(): void
    {
        $data = $this->form->getState();

        $ip = request()->ip();
        $key = 'booking-submit:'.$ip;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'data.customer_email' => __('Too many booking attempts. Please try again in :seconds seconds.', ['seconds' => $seconds]),
            ]);
        }

        RateLimiter::hit($key, 60);

        $isLoggedIn = Auth::check();

        if ($isLoggedIn) {
            $user = Auth::user();
        } else {
            if (User::where('email', $data['customer_email'])->exists()) {
                throw ValidationException::withMessages([
                    'data.customer_email' => __('A user with this email already exists. Please log in or use a different email.'),
                ]);
            }
        }

        try {
            DB::beginTransaction();

            if ($isLoggedIn) {
                $user = Auth::user();
            } else {
                $user = User::create([
                    'name' => $data['customer_name'],
                    'email' => $data['customer_email'],
                    'password' => Hash::make($data['customer_password']),
                ]);

                Auth::login($user);

                $personData = [
                    'user_id' => $user->id,
                    'type' => 'customer',
                    'phone' => $data['customer_phone'],
                    'address' => $data['customer_address'],
                    'id_type' => $data['customer_id_type'],
                ];

                if (! empty($data['id_file'])) {
                    $personData['id_file_path'] = $data['id_file'];
                }

                Person::create($personData);
            }

            $vehicle = $this->getVehicle();

            $bookingReference = 'BRK-'.strtoupper(Str::random(7));

            $rentalData = [
                'booking_reference' => $bookingReference,
                'user_id' => $user->id,
                'vehicle_id' => $vehicle->id,
                'start_date' => $this->startDateTime,
                'end_date' => $this->endDateTime,
                'rental_rate_per_day' => $vehicle->rental_rate_per_day,
                'total_amount' => $this->totalAmount,
                'status' => RentalStatus::Pending->value,
                'delivery_method' => $data['delivery_method'] ?? null,
                'delivery_address' => ($data['delivery_method'] ?? null) === 'delivery' ? ($data['delivery_address'] ?? null) : null,
            ];

            if ($this->driverOption === 'with-driver' && $this->driverId) {
                $driver = Person::find($this->driverId);
                $rentalData['driver_id'] = $this->driverId;
                $rentalData['driver_fee_per_day'] = $driver->driver_fee_per_day;
            }

            $rental = Rental::create($rentalData);

            $isTransfer = ($data['payment_method'] ?? '') === 'transfer';

            Payment::create([
                'rental_id' => $rental->id,
                'amount' => $this->totalAmount,
                'method' => $data['payment_method'],
                'bank_id' => $isTransfer ? ($data['bank_id'] ?? null) : null,
                'proof_file_path' => $isTransfer ? ($data['payment_proof'] ?? null) : null,

                'date' => now()->toDateString(),
            ]);

            DB::commit();

            $this->dispatch('booking-completed', redirectUrl: '/dashboard', bookingReference: $bookingReference);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.booking-wizard');
    }
}
