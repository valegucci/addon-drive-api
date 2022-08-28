# Move Google Drive files folder to folder
Laravel package that installs an API route to move files between Google Drive folders

## Installation
Open the composer.json file of your main laravel installation and put the following code to retrieve the git repository.

    "repositories": [
    	{
    		"url": "https://github.com/valegucci/addon-drive-api.git",
    		"type": "git"
    	}
    ],
and then put package into dependecies:

    "require": {
    	"vale/addon-drive": "^0.1"
    }

after the modifications you can run the composer update command:

    composer update

## SETUP
Add DriveProvider to config/app.php:

    'providers' => [ 
    	Vale\Addons\Drive\DriveProvider::class,
    ],
Next publish the package's configuration file by running:

    php artisan vendor:publish --provider="Vale\Addons\Drive\DriveProvider"

## Configuration
Set the Google Service account key file path and in the application env file:

    GOOGLE_APPLICATION_CREDENTIALS=google-service-account.json

and also the desired API key to protect the API against unauthorized access:

    DRIVE_API_KEY=123456789

## Making Requests
Here's the example cURL request to move Google Drive files folder to folder:

     curl --location --request POST 'http://your-domain.com/api/move-file' \
    --header 'Content-Type: application/json' \
    --data-raw '{
    "file_id": "1aqxAb6g_NJfurM8JKMACANNtkSucdPkp",
    "folder_id": "1a6F798LKvsPRlmrnh7wRNyaIjD2cs8pa"
    }'

