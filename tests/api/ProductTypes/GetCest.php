<?php

namespace Niden\Tests\api\ProductTypes;

use ApiTester;
use Niden\Constants\Resources;
use Niden\Models\ProductTypes;
use Niden\Models\Users;
use Page\Data;
use function uniqid;

class GetCest
{
    public function getProductTypes(ApiTester $I)
    {
        $this->addRecord($I);
        $token = $I->apiLogin();

        $typeOne = $I->haveRecordWithFields(
            ProductTypes::class,
            [
                'prt_name' => uniqid('type-a-'),
            ]
        );
        $typeTwo = $I->haveRecordWithFields(
            ProductTypes::class,
            [
                'prt_name' => uniqid('type-b-'),
            ]
        );
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendGET(Data::$productTypesUrl);
        $I->deleteHeader('Authorization');
        $I->seeResponseIsSuccessful();
        $I->seeSuccessJsonResponse(
            [
                [
                    'id'         => $typeOne->get('prt_id'),
                    'type'       => Resources::PRODUCT_TYPES,
                    'attributes' => [
                        'name'        => $typeOne->get('prt_name'),
                        'description' => $typeOne->get('prt_description'),
                    ],
                ],
                [
                    'id'         => $typeTwo->get('prt_id'),
                    'type'       => Resources::PRODUCT_TYPES,
                    'attributes' => [
                        'name'        => $typeTwo->get('prt_name'),
                        'description' => $typeTwo->get('prt_description'),
                    ],
                ],
            ]
        );
    }

    public function getProductTypesNoData(ApiTester $I)
    {
        $this->addRecord($I);
        $token = $I->apiLogin();

        $I->haveHttpHeader('Authorization', 'Bearer ' . $token);
        $I->sendGET(Data::$productTypesUrl);
        $I->deleteHeader('Authorization');
        $I->seeResponseIsSuccessful();
        $I->seeSuccessJsonResponse();
    }

    private function addRecord(ApiTester $I)
    {
        return $I->haveRecordWithFields(
            Users::class,
            [
                'usr_status_flag'    => 1,
                'usr_username'       => 'testuser',
                'usr_password'       => 'testpassword',
                'usr_issuer'         => 'https://niden.net',
                'usr_token_password' => '12345',
                'usr_token_id'       => '110011',
            ]
        );
    }
}
