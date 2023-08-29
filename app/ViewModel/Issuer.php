<?php
namespace App\ViewModel;

use App\Models\Master\Company;
use App\Traits\ToJson;
use PDO;

class Issuer{
    use ToJson;
    public function __construct($invoiceHeader)
    {
        $company = Company::where('branch_id',$invoiceHeader->branch_code)->first();
        if(is_null($company)){
            $company = Company::first();
        }
        $address = $company->getTaxAddress();
        $this->address = $address;
        $this->address->branchID = $invoiceHeader->branch_code ?? $company->branch_id;
        $this->type = $company->type;
        $this->id = $company->tax_id;
        $this->name = trim($company->tax_company_name);
    }
    public $address = [
        "branchID"=> "",
        "country"=> "",
        "governate"=> "",
        "regionCity"=> "",
        "street"=> "",
        "buildingNumber"=> "",
        "postalCode"=> "",
        "floor"=> "",
        "room"=> "",
        "landmark"=> "",
        "additionalInformation"=> ""
    ];

    public $type = "";
    public $id = "";
    public $name = "";
}
