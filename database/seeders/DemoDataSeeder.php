<?php

namespace Database\Seeders;

use App\Enum\VaultStates;
use App\Models\LoanScheme;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
	public function run()
	{
		$user = User::create([
			'userId'   => 'demo-demo-demo-demo-demodemodemo',
			'language' => 'en',
			'theme'    => 'dark',
		]);
		$vaultActive = Vault::create([
			'vaultId'            => 'active_demo__e888c4ea3dd06bd1bf30a4dfa38c625f8fd430e9d321607',
			'loanSchemeId'       => LoanScheme::where('name', '=', 'C150')->first()->id,
			'ownerAddress'       => 'tedT9idRxCzmmxT4sST9gHmAZ5Mh24a2Wm',
			'state'              => VaultStates::ACTIVE,
			'collateralAmounts'  => ["4000.00000000@DFI"],
			'loanAmounts'        => ["23.53489032@MSFT"],
			'interestAmounts'    => ["0.00053436@MSFT"],
			'collateralValue'    => 12000.00000000,
			'loanValue'          => 7840.17801230,
			'interestValue'      => 0.17801134,
			'informativeRatio'   => 153.05774921,
			'collateralRatio'    => 153,
			'liquidationHeight'  => 0,
			'batchCount'         => 0,
			'liquidationPenalty' => 0,
			'batches'            => [],
		]);
		$vaultInLiquidation = Vault::create([
			'vaultId'            => 'in_liquidation_demo__e888c4ea3dd06bd1bf30a4dfa38c625f8fd430e9d321607',
			'loanSchemeId'       => LoanScheme::where('name', '=', 'C150')->first()->id,
			'ownerAddress'       => 'trGCtcntm42AMLw3fhaDPwBMYTikTMYzFm',
			'state'              => VaultStates::INLIQUIDATION,
			'collateralAmounts'  => [],
			'loanAmounts'        => [],
			'interestAmounts'    => [],
			'collateralValue'    => null,
			'loanValue'          => null,
			'interestValue'      => 0,
			'informativeRatio'   => 0,
			'collateralRatio'    => null,
			'liquidationHeight'  => 688650,
			'batchCount'         => 2,
			'liquidationPenalty' => 5,
			'batches'            => json_encode('[{"index":0,"collaterals":["3333.33331948@DFI"],"loan":"2.29968141@GOOGL","highestBid":{"owner":"tedT9idRxCzmmxT4sST9gHmAZ5Mh24a2Wm","amount":"2.41466548@GOOGL"}},{"index":1,"collaterals":["573.61003990@DFI"],"loan":"0.39573611@GOOGL","highestBid":{"owner":"tedT9idRxCzmmxT4sST9gHmAZ5Mh24a2Wm","amount":"0.41552292@GOOGL"}}]'),
		]);
		$vaultMayLiquidate = Vault::create([
			'vaultId'            => 'may_liquidate_demo__e888c4ea3dd06bd1bf30a4dfa38c625f8fd430e9d321607',
			'loanSchemeId'       => LoanScheme::where('name', '=', 'C150')->first()->id,
			'ownerAddress'       => 'tedT9idRxCzmmxT4sST9gHmAZ5Mh24a2Wm',
			'state'              => VaultStates::MAYLIQUIDATE,
			'collateralAmounts'  => ["3906.94274903@DFI"],
			'loanAmounts'        => ["6.65934556@TSLA"],
			'interestAmounts'    => ["0.00005451@TSLA"],
			'collateralValue'    => 11720.82824709,
			'loanValue'          => 7804.75299632,
			'interestValue'      => 0.06388572,
			'informativeRatio'   => 150.17551808,
			'collateralRatio'    => 150,
			'liquidationHeight'  => 0,
			'batchCount'         => 0,
			'liquidationPenalty' => 0,
			'batches'            => [],
		]);

		$user->vaults()->attach([$vaultActive->vaultId, $vaultInLiquidation->vaultId, $vaultMayLiquidate->vaultId]);
	}
}
