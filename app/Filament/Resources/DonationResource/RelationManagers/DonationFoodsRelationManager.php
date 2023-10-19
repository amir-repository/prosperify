<?php

namespace App\Filament\Resources\DonationResource\RelationManagers;

use App\Models\DonationAssignment;
use App\Models\DonationFood;
use App\Models\Food;
use App\Models\FoodDonationGivenReceipt;
use App\Models\FoodDonationLog;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class DonationFoodsRelationManager extends RelationManager
{
    protected static string $relationship = 'DonationFoods';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('food_id')->label('Food')->options(
                    Food::with('unit')
                        ->where('expired_date', '>=', Carbon::now())
                        ->where('amount', '>', 0)
                        ->where('food_rescue_status_id', Food::STORED)
                        ->orWhere('food_rescue_status_id', Food::ADJUSTED_AFTER_STORED)
                        ->get()->mapWithKeys(fn ($x) => [$x->id => "$x->name ($x->amount." . $x->unit->name . ")"])
                )->searchable()->required(),
                TextInput::make('amount')->integer()->required(),
                FileUpload::make('photo')->image()->disk('public')->directory('donation-documentations')->required()->columnSpan(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('food.name'),
                TextColumn::make('amount'),
                TextColumn::make('food.unit.name'),
                TextColumn::make('foodDonationStatus.name')->label('Status'),
                TextColumn::make('food.id')->label('Photo'),
                TextColumn::make('food.vault_id')->label('Receipt')

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                    $donation = $this->getOwnerRecord();
                    $data['donation_id'] = $donation->id;
                    $data['food_donation_status_id'] = DonationFood::GIVEN;
                    return $data;
                })->after(function (DonationFood $donationFood) {
                    $user = auth()->user();
                    $foodDonationLogPhoto = reset($this->mountedTableActionsData[0]['photo']);
                    $receiptGivenPhoto = reset($this->mountedTableActionsData[0]['receipt-photo']);

                    try {
                        DB::beginTransaction();
                        $donationAssignment = new DonationAssignment();
                        $donationAssignment->volunteer_id = $user->id;
                        $donationAssignment->vault_id = $donationFood->food->vault_id;
                        $donationAssignment->assigner_id = $user->id;
                        $donationAssignment->donation_food_id = $donationFood->id;
                        $donationAssignment->donation_id = $donationFood->donation_id;
                        $donationAssignment->food_id = $donationFood->food_id;
                        $donationAssignment->save();

                        // create donation food log
                        FoodDonationLog::Create($donationFood, $user, $foodDonationLogPhoto);

                        // create donation given receipt
                        $foodDonationGivenReceipt = new FoodDonationGivenReceipt();
                        $foodDonationGivenReceipt->donation_assignment_id = $donationAssignment->id;
                        $foodDonationGivenReceipt->given_amount = $donationFood->amount;
                        $foodDonationGivenReceipt->receipt_photo = $receiptGivenPhoto;
                        $foodDonationGivenReceipt->save();

                        // kurangi food resourcenya
                        $food = Food::find($donationFood->food_id);
                        $food->amount = $food->amount - $donationFood->amount;
                        $food->save();
                        DB::commit();
                    } catch (\Exception $th) {
                        throw $th;
                    }
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
