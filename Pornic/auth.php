<?php
require_once(__DIR__ . '/vendor/autoload.php');



// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIwMjczMGE0YmI2NjI0Y2MyOTJkNDA4ZGUwNjM1ZmJjYyIsInVycyI6Ik9yZ2FuaXphdGlvbkFkbWluIiwiY3BzIjpbIkFjY2Vzc1B1YmxpY0RhdGEiLCJBY2Nlc3NUcmFuc2FjdGlvbnMiLCJDaGVja291dCIsIlJlZnVuZE1hbmFnZW1lbnQiXSwibmJmIjoxNzU5OTQxMzg5LCJleHAiOjE3NTk5NDMxODksImlzcyI6Imh0dHBzOi8vYXBpLmhlbGxvYXNzby1zYW5kYm94LmNvbSIsImF1ZCI6ImNlYWNhMTlhYzk0NTRmY2NhNDIxZjMxNjczY2Y2ZWZmIn0.Ug2JDlvLWeNgaIl58LBcKc0RHcUJyDuJ0L7_w_4xOGEyFn1h3EpjvB_HL9pVwmEV9cm_d2HBmNl72n9iJhu9T2WCK6UHrlbgqtLML5UZ9PpQ78oLGsYx1aEsm-6ID0RDPjwuOnlO1pb09UUwwSoQd9oddh0H-a6v8U9byTuLeMS0NyBUMlKIHBSCDxZnKvioG55Sqp7GGmK_7_XN7wQjXYbI6wrOPw0jrrJ_eCZdJ143Z8lhihKBPXvcDKFTo3LU_8afDtPD6_12uHF8IxmBu-i54zEgni85ZrUM4ZewBSaew3Q-iib-vp0N0e68LV6Q-UuNshQ4W2SjMtvcmemvmQ');


$apiInstance = new OpenAPI\Client\Api\AnnuaireApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$page_size = 20; // int | The number of items per page
$continuation_token = 'continuation_token_example'; // string | Continuation Token from which we wish to retrieve results
$hello_asso_api_v5_models_directory_list_forms_request = new \OpenAPI\Client\Model\HelloAssoApiV5ModelsDirectoryListFormsRequest(); // \OpenAPI\Client\Model\HelloAssoApiV5ModelsDirectoryListFormsRequest | Body which contains the filters to apply

