<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;
use RachidLaasri\LaravelInstaller\Events\EnvironmentSaved;
use Illuminate\Validation\Rule;

class NewEnvironmentController extends Controller
{
	use CommonTrait;
	protected $EnvironmentManager;
	public function __construct(EnvironmentManager $environmentManager)
	{
		$this->EnvironmentManager = $environmentManager;
	}
	public function environmentMenu()
	{
		return view('vendor.installer.environment');
	}
	public function environmentWizard()
	{
		$envConfig = $this->EnvironmentManager->getEnvContent();
		return view('vendor.installer.environment-wizard', compact('envConfig'));
	}
	public function environmentClassic()
	{
		$envConfig = $this->EnvironmentManager->getEnvContent();
		return view('vendor.installer.environment-classic', compact('envConfig'));
	}
	public function saveClassic(Request $input, Redirector $redirect)
	{
		$message = $this->EnvironmentManager->saveFileClassic($input);
		event(new EnvironmentSaved($input));
		return $redirect->route('LaravelInstaller::environmentClassic')->with(['message' => $message]);
	}
	public function saveEnvWizard(Request $request, Redirector $redirect)
	{
		$rules = config('installer.environment.form.rules');
		$messages = ['environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			$errors = $validator->errors();
			$envConfig = $this->EnvironmentManager->getEnvContent();
			return view('vendor.installer.environment-wizard', compact('errors', 'envConfig'));
		}
		$user_name = $request->user_name;
		$purchase_code = $request->purchase_code;
		$installedLogFile = storage_path('iniSys');
		$dateStamp = date("Y/m/d h:i:sa");
		if (!file_exists($installedLogFile)) {
			$message = $user_name;
			$message .= "\n" . $purchase_code;
			$message = "Valid license";
			file_put_contents($installedLogFile, $message);
		} else {
			$message = trans('installer_messages.updater.log.success_message') . $dateStamp;
			file_put_contents($installedLogFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
		}
		$results = $this->EnvironmentManager->saveFileWizard($request);
		Artisan::call('storage:link');
		event(new EnvironmentSaved($request));
		Artisan::call('cache:clear');
		return $redirect->route('LaravelInstaller::database')->with(['results' => $results]);
	}
}
